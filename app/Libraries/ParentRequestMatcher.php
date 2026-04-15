<?php

namespace App\Libraries;

use App\Models\TutorSubscriptionModel;
use Config\Database;

class ParentRequestMatcher
{
    public function findQualifiedTutors(array $request): array
    {
        (new TutorSubscriptionModel())->markExpiredSubscriptions();

        $now = date('Y-m-d H:i:s');
        $rows = Database::connect()
            ->table('users')
            ->distinct()
            ->select(
                'users.id, users.username, users.first_name, users.last_name, users.email, users.phone, '
                . 'users.whatsapp_number, users.district, users.location, users.teaching_mode, '
                . 'users.structured_subjects, users.subscription_plan, users.subscription_expires_at, '
                . 'users.rating, users.review_count, users.search_count, users.is_active, '
                . 'users.is_verified, users.tutor_status'
            )
            ->join('tutor_subscriptions', 'tutor_subscriptions.user_id = users.id', 'inner')
            ->where('users.role', 'trainer')
            ->where('users.is_active', 1)
            ->where('users.is_verified', 1)
            ->where('users.deleted_at', null)
            ->whereIn('users.tutor_status', ['approved', 'active'])
            ->where('users.email !=', '')
            ->where('tutor_subscriptions.status', 'active')
            ->where('tutor_subscriptions.payment_status', 'verified')
            ->where('tutor_subscriptions.payment_amount >', 0)
            ->where('tutor_subscriptions.current_period_start <=', $now)
            ->where('tutor_subscriptions.current_period_end >=', $now)
            ->get()
            ->getResultArray();

        return array_values(array_filter($rows, fn (array $tutor) => $this->isTutorQualifiedForRequest($request, $tutor)));
    }

    public function isTutorQualifiedForRequest(array $request, array $tutor): bool
    {
        return $this->hasActiveTutorProfile($tutor)
            && $this->matchesRequestedSubjects($request, $tutor)
            && $this->matchesRequestedModeAndLocation($request, $tutor)
            && $this->hasActivePaidSubscription((int) ($tutor['id'] ?? 0));
    }

    public function decodeSubjects(?string $subjectsJson): array
    {
        $subjects = json_decode((string) $subjectsJson, true);

        return is_array($subjects) ? array_values(array_filter($subjects)) : [];
    }

    public function tutorSubjectNames(?string $structuredSubjectsJson): array
    {
        $structuredSubjects = json_decode((string) $structuredSubjectsJson, true);

        if (!is_array($structuredSubjects)) {
            return [];
        }

        $subjects = [];

        foreach ($structuredSubjects as $curriculumData) {
            if (empty($curriculumData['levels']) || !is_array($curriculumData['levels'])) {
                continue;
            }

            foreach ($curriculumData['levels'] as $levelSubjects) {
                if (!is_array($levelSubjects)) {
                    continue;
                }

                foreach ($levelSubjects as $subject) {
                    $subject = trim((string) $subject);
                    if ($subject !== '') {
                        $subjects[] = $subject;
                    }
                }
            }
        }

        return array_values(array_unique($subjects));
    }

    private function hasActivePaidSubscription(int $tutorId): bool
    {
        if ($tutorId <= 0) {
            return false;
        }

        $now = date('Y-m-d H:i:s');

        $subscription = Database::connect()
            ->table('tutor_subscriptions')
            ->where('user_id', $tutorId)
            ->where('status', 'active')
            ->where('payment_status', 'verified')
            ->where('payment_amount >', 0)
            ->where('current_period_start <=', $now)
            ->where('current_period_end >=', $now)
            ->orderBy('current_period_end', 'DESC')
            ->get(1)
            ->getRowArray();

        return (bool) $subscription;
    }

    private function hasActiveTutorProfile(array $tutor): bool
    {
        if (isset($tutor['is_active']) && (int) $tutor['is_active'] !== 1) {
            return false;
        }

        if (isset($tutor['is_verified']) && (int) $tutor['is_verified'] !== 1) {
            return false;
        }

        if (isset($tutor['tutor_status']) && !in_array((string) $tutor['tutor_status'], ['approved', 'active'], true)) {
            return false;
        }

        if (isset($tutor['email']) && trim((string) $tutor['email']) === '') {
            return false;
        }

        return true;
    }

    private function matchesRequestedSubjects(array $request, array $tutor): bool
    {
        $requestedSubjects = $request['subjects'] ?? $this->decodeSubjects($request['subjects_json'] ?? '[]');
        $requestedSubjects = array_map([$this, 'normalizeComparable'], $requestedSubjects);
        $requestedSubjects = array_filter($requestedSubjects);

        if (empty($requestedSubjects) || empty($tutor['structured_subjects'])) {
            return false;
        }

        $structuredSubjects = json_decode((string) $tutor['structured_subjects'], true);

        if (!is_array($structuredSubjects)) {
            return false;
        }

        $tutorSubjects = [];
        $curriculum = (string) ($request['curriculum'] ?? '');
        $curriculaToSearch = isset($structuredSubjects[$curriculum])
            ? [$structuredSubjects[$curriculum]]
            : array_values($structuredSubjects);

        foreach ($curriculaToSearch as $curriculumData) {
            if (empty($curriculumData['levels']) || !is_array($curriculumData['levels'])) {
                continue;
            }

            foreach ($curriculumData['levels'] as $levelSubjects) {
                if (!is_array($levelSubjects)) {
                    continue;
                }

                foreach ($levelSubjects as $subject) {
                    $tutorSubjects[] = $this->normalizeComparable((string) $subject);
                }
            }
        }

        return count(array_intersect($requestedSubjects, $tutorSubjects)) > 0;
    }

    private function matchesRequestedModeAndLocation(array $request, array $tutor): bool
    {
        $requestMode = strtolower((string) ($request['mode'] ?? ''));
        $teachingMode = $this->normalizeMode((string) ($tutor['teaching_mode'] ?? ''));

        if ($requestMode === 'online') {
            return in_array($teachingMode, ['online', 'both'], true);
        }

        if ($requestMode === 'physical') {
            $sameDistrict = $this->normalizeComparable((string) ($request['district'] ?? ''))
                === $this->normalizeComparable((string) ($tutor['district'] ?? ''));

            return $sameDistrict && in_array($teachingMode, ['physical', 'both'], true);
        }

        return false;
    }

    private function normalizeMode(string $mode): string
    {
        $mode = strtolower(trim($mode));

        if (in_array($mode, ['online', 'online only'], true)) {
            return 'online';
        }

        if (in_array($mode, ['physical', 'in-person', 'in-person only', 'physical only'], true)) {
            return 'physical';
        }

        if (str_contains($mode, 'both')) {
            return 'both';
        }

        return $mode;
    }

    private function normalizeComparable(string $value): string
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', $value) ?? ''));
    }
}

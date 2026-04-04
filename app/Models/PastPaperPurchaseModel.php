<?php

namespace App\Models;

use CodeIgniter\Model;

class PastPaperPurchaseModel extends Model
{
    protected $table = 'past_paper_purchases';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'past_paper_id',
        'user_id',
        'tx_ref',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'access_token',
        'paid_at',
        'download_granted_at',
        'last_downloaded_at',
        'download_count',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    public function getAccessiblePaperIds(array $paperIds, ?int $userId = null, ?string $email = null, array $accessTokens = []): array
    {
        $paperIds = array_values(array_unique(array_filter(array_map('intval', $paperIds))));
        $accessTokens = array_values(array_unique(array_filter(array_map('strval', $accessTokens))));
        $email = $this->normalizeEmail($email);

        if ($paperIds === []) {
            return [];
        }

        $builder = $this->builder();
        $builder->distinct()
            ->select('past_paper_id')
            ->whereIn('past_paper_id', $paperIds)
            ->where('payment_status', 'verified');

        $hasIdentityCondition = false;
        $builder->groupStart();

        if ($userId) {
            $builder->where('user_id', $userId);
            $hasIdentityCondition = true;
        }

        if ($email !== null) {
            if ($hasIdentityCondition) {
                $builder->orWhere('buyer_email', $email);
            } else {
                $builder->where('buyer_email', $email);
            }
            $hasIdentityCondition = true;
        }

        if ($accessTokens !== []) {
            if ($hasIdentityCondition) {
                $builder->orWhereIn('access_token', $accessTokens);
            } else {
                $builder->whereIn('access_token', $accessTokens);
            }
            $hasIdentityCondition = true;
        }

        $builder->groupEnd();

        if (!$hasIdentityCondition) {
            return [];
        }

        $rows = $builder->get()->getResultArray();

        return array_map('intval', array_column($rows, 'past_paper_id'));
    }

    public function findLatestVerifiedAccess(int $paperId, ?int $userId = null, ?string $email = null, ?string $accessToken = null): ?array
    {
        $paperId = (int) $paperId;
        $email = $this->normalizeEmail($email);
        $accessToken = trim((string) $accessToken);

        if ($paperId <= 0) {
            return null;
        }

        $builder = $this->builder();
        $builder->where('past_paper_id', $paperId)
            ->where('payment_status', 'verified');

        $hasIdentityCondition = false;
        $builder->groupStart();

        if ($userId) {
            $builder->where('user_id', $userId);
            $hasIdentityCondition = true;
        }

        if ($email !== null) {
            if ($hasIdentityCondition) {
                $builder->orWhere('buyer_email', $email);
            } else {
                $builder->where('buyer_email', $email);
            }
            $hasIdentityCondition = true;
        }

        if ($accessToken !== '') {
            if ($hasIdentityCondition) {
                $builder->orWhere('access_token', $accessToken);
            } else {
                $builder->where('access_token', $accessToken);
            }
            $hasIdentityCondition = true;
        }

        $builder->groupEnd();

        if (!$hasIdentityCondition) {
            return null;
        }

        $row = $builder
            ->orderBy('paid_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get(1)
            ->getFirstRow('array');

        return $row ?: null;
    }

    public function findLatestVerifiedByEmail(int $paperId, string $email): ?array
    {
        $email = $this->normalizeEmail($email);
        if ($email === null) {
            return null;
        }

        $row = $this->builder()
            ->where('past_paper_id', $paperId)
            ->where('buyer_email', $email)
            ->where('payment_status', 'verified')
            ->orderBy('paid_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get(1)
            ->getFirstRow('array');

        return $row ?: null;
    }

    public function findByTxRef(string $txRef): ?array
    {
        $txRef = trim($txRef);
        if ($txRef === '') {
            return null;
        }

        $row = $this->builder()
            ->where('tx_ref', $txRef)
            ->get(1)
            ->getFirstRow('array');

        return $row ?: null;
    }

    public function findByTxRefForPaper(string $txRef, int $paperId): ?array
    {
        $txRef = trim($txRef);
        $paperId = (int) $paperId;

        if ($txRef === '' || $paperId <= 0) {
            return null;
        }

        $row = $this->builder()
            ->where('tx_ref', $txRef)
            ->where('past_paper_id', $paperId)
            ->get(1)
            ->getFirstRow('array');

        return $row ?: null;
    }

    public function incrementGrantedDownloadCount(int $purchaseId): bool
    {
        return (bool) $this->where('id', $purchaseId)
            ->set('download_count', 'download_count + 1', false)
            ->set('last_downloaded_at', date('Y-m-d H:i:s'))
            ->update();
    }

    private function normalizeEmail(?string $email): ?string
    {
        $normalized = strtolower(trim((string) $email));
        return $normalized !== '' ? $normalized : null;
    }
}

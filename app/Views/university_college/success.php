<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<section class="bg-gray-50 min-h-[70vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="bg-white border border-green-200 rounded-xl shadow-sm p-8">
            <div class="w-14 h-14 rounded-full bg-green-100 text-green-700 mx-auto flex items-center justify-center text-2xl">✓</div>
            <h1 class="mt-4 text-3xl font-extrabold text-secondary text-center">Registration Complete</h1>
            <p class="mt-3 text-gray-700 text-center">Your University & College Support tutor profile has been received. Follow the next steps below.</p>

            <div class="mt-8 space-y-4">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-sm font-semibold text-primary uppercase tracking-wide">Step 1</p>
                    <h2 class="mt-1 text-lg font-bold text-secondary">Wait for review</h2>
                    <p class="mt-2 text-gray-700">Our admin team will review your tutor profile and confirm the details you submitted.</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-sm font-semibold text-primary uppercase tracking-wide">Step 2</p>
                    <h2 class="mt-1 text-lg font-bold text-secondary">Prepare your support services</h2>
                    <p class="mt-2 text-gray-700">Get ready to offer tutoring, dissertation support, consultation, and exam preparation services.</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-sm font-semibold text-primary uppercase tracking-wide">Step 3</p>
                    <h2 class="mt-1 text-lg font-bold text-secondary">Start receiving requests</h2>
                    <p class="mt-2 text-gray-700">Once approved, students can reach you through the University & College Support portal.</p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="<?= site_url('university-college-support') ?>" class="inline-flex items-center rounded-lg border border-primary px-6 py-3 text-primary font-bold hover:bg-primary hover:text-white transition">Back to Module</a>
                <a href="<?= site_url('request-tutor?type=university') ?>" class="inline-flex items-center rounded-lg bg-primary px-6 py-3 text-white font-bold hover:bg-red-600 transition">Request University Support</a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

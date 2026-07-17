<?= $this->extend('layouts/admin') ?>

<?php $active_page = 'library'; ?>
<?php $title = $title ?? 'Library Management'; ?>

<?= $this->section('content') ?>

    <!-- Header -->
    <div class="header-bar">
        <div>
            <h1 class="page-title">Library Management</h1>
            <p class="page-subtitle">Manage educational resources and content</p>
        </div>
        <div class="dropdown">
            <button class="btn-admin dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-plus me-2"></i>Add Resource
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPaperModal">
                    <i class="fas fa-file-pdf text-danger me-2"></i>Past Paper
                </a></li>
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addVideoModal">
                    <i class="fas fa-video text-info me-2"></i>Video Solution
                </a></li>
            </ul>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="stat-number"><?php echo number_format($stats['total_papers'] ?? 0); ?></div>
            <div class="stat-label">Total Papers</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-video"></i>
            </div>
            <div class="stat-number"><?php echo number_format($stats['total_videos'] ?? 0); ?></div>
            <div class="stat-label">Total Videos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number"><?php echo number_format(($stats['active_papers'] ?? 0) + ($stats['approved_videos'] ?? 0)); ?></div>
            <div class="stat-label">Active Resources</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number"><?php echo number_format($pendingCount); ?></div>
            <div class="stat-label">Pending Approval</div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <!-- Filters Section -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding: 20px; background: rgba(0, 0, 0, 0.02); border-radius: 12px;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <span style="color: var(--text-light);"><i class="fas fa-filter me-2"></i>Filter by:</span>
                <div class="btn-group" role="group">
                    <a href="<?= site_url('admin/library') ?>" class="btn btn-sm <?= empty($type_filter) ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="fas fa-th me-1"></i>All Resources
                    </a>
                    <a href="<?= site_url('admin/library?type=past_paper') ?>" class="btn btn-sm <?= $type_filter === 'past_paper' ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="fas fa-file-pdf me-1"></i>Past Papers
                    </a>
                    <a href="<?= site_url('admin/library?type=video') ?>" class="btn btn-sm <?= $type_filter === 'video' ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="fas fa-video me-1"></i>Videos
                    </a>
                </div>
            </div>
        </div>

        <?php if (!empty($resources)): ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="libraryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Subject</th>
                            <th>Curriculum</th>
                            <th>Grade</th>
                            <th>Added By</th>
                            <th>Status</th>
                            <th>Stats</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resources as $resource): ?>
                            <tr>
                                <td>
                                    <?php if ($resource['resource_type'] === 'video'): ?>
                                        <span class="badge bg-primary"><i class="fas fa-video me-1"></i>Video</span>
                                    <?php else: ?>
                                        <span class="badge bg-dark"><i class="fas fa-file-pdf me-1"></i>Paper</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= esc($resource['title']) ?></strong>
                                    <?php if ($resource['is_featured']): ?>
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    <?php endif; ?>
                                    <?php if ($resource['resource_type'] === 'past_paper'): ?>
                                        <div class="mt-1">
                                            <?php if (!empty($resource['is_paid'])): ?>
                                                <span class="badge bg-warning text-dark">
                                                    Paid • MK <?= number_format((float) ($resource['price'] ?? 0), 0) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Free Download</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($resource['subject']) ?></td>
                                <td><?= esc($resource['curriculum']) ?></td>
                                <td><?= esc($resource['grade_level']) ?></td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        <?= esc($resource['uploaded_by_name'] ?? 'Unknown') ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($resource['is_approved']): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        <i class="fas fa-eye text-muted"></i> <?= $resource['view_count'] ?>
                                        <?php if ($resource['resource_type'] === 'past_paper'): ?>
                                            <i class="fas fa-download text-muted ms-2"></i> <?= $resource['download_count'] ?>
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td><small class="text-muted"><?= $resource['created_at'] ? date('M d, Y', strtotime($resource['created_at'])) : '-' ?></small></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button onclick="editResource(<?= $resource['id'] ?>, '<?= $resource['resource_type'] ?>')" class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <?php if ($resource['resource_type'] === 'video' && !empty($resource['video_embed_code'])): ?>
                                            <button onclick="watchVideo(this)" data-embed="<?= htmlspecialchars($resource['video_embed_code'], ENT_QUOTES) ?>" data-title="<?= htmlspecialchars($resource['title'], ENT_QUOTES) ?>" class="btn btn-outline-info" title="Watch Video">
                                                <i class="fas fa-play"></i>
                                            </button>

                                            <?php if (!$resource['is_approved']): ?>
                                                <button onclick="approveVideo(<?= $resource['id'] ?>)" class="btn btn-outline-success" title="Approve Video">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button onclick="rejectVideo(<?= $resource['id'] ?>)" class="btn btn-outline-warning" title="Reject Video">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <button onclick="deleteResource(<?= $resource['id'] ?>, '<?= $resource['resource_type'] ?>')" class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (!empty($pager) && ($pager['total_pages'] ?? 0) > 1): ?>
                <?php
                    $currentPage = (int) ($pager['current_page'] ?? 1);
                    $totalPages = (int) ($pager['total_pages'] ?? 1);
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);
                    $query = $_GET ?? [];
                ?>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                    <div>
                        <small class="text-muted">
                            Showing <?= $pager['start_item'] ?? 0 ?>-<?= $pager['end_item'] ?? 0 ?> of <?= $pager['total_items'] ?? 0 ?> resources
                        </small>
                    </div>

                    <nav aria-label="Library pagination">
                        <ul class="pagination mb-0">
                            <?php if ($currentPage > 1): ?>
                                <?php $prevQuery = array_merge($query, ['page' => $currentPage - 1]); ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= current_url() . '?' . http_build_query($prevQuery) ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php if ($startPage > 1): ?>
                                <?php $firstQuery = array_merge($query, ['page' => 1]); ?>
                                <li class="page-item <?= $currentPage === 1 ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= current_url() . '?' . http_build_query($firstQuery) ?>">1</a>
                                </li>
                                <?php if ($startPage > 2): ?>
                                    <li class="page-item disabled"><span class="page-link">…</span></li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++): ?>
                                <?php $pageQuery = array_merge($query, ['page' => $pageNumber]); ?>
                                <li class="page-item <?= $pageNumber === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= current_url() . '?' . http_build_query($pageQuery) ?>"><?= $pageNumber ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($endPage < $totalPages): ?>
                                <?php if ($endPage < $totalPages - 1): ?>
                                    <li class="page-item disabled"><span class="page-link">…</span></li>
                                <?php endif; ?>
                                <?php $lastQuery = array_merge($query, ['page' => $totalPages]); ?>
                                <li class="page-item <?= $currentPage === $totalPages ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= current_url() . '?' . http_build_query($lastQuery) ?>"><?= $totalPages ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <?php $nextQuery = array_merge($query, ['page' => $currentPage + 1]); ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= current_url() . '?' . http_build_query($nextQuery) ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px;">
                <div style="font-size: 48px; color: var(--text-light); margin-bottom: 20px;">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 style="color: var(--text-light); margin-bottom: 16px;">No Resources Yet</h3>
                <p style="color: var(--text-light); margin-bottom: 24px;">Start building your educational library by adding resources.</p>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-plus me-2"></i>Add Your First Resource
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPaperModal">
                            <i class="fas fa-file-pdf text-danger me-2"></i>Past Paper
                        </a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addVideoModal">
                            <i class="fas fa-video text-info me-2"></i>Video Solution
                        </a></li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Past Paper Modal -->
<div class="modal fade" id="addPaperModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-pdf mr-2"></i>Add Past Paper</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPaperForm" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div id="addPaperErrors" class="alert alert-danger" style="display:none;"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Curriculum <span class="text-danger">*</span></label>
                                <select name="exam_body" id="paper_exam_body" class="form-control" required>
                                    <option value="">Select Curriculum</option>
                                    <?php foreach ($curriculums as $curriculum): ?>
                                        <option value="<?= esc($curriculum) ?>"><?= esc($curriculum) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Level <span class="text-danger">*</span></label>
                                <select name="exam_level" id="paper_exam_level" class="form-control" required>
                                    <option value="">Select Curriculum First</option>
                                </select>
                                <small class="form-text text-muted">Choose curriculum to see levels</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subject <span class="text-danger">*</span></label>
                                <select name="subject" id="paper_subject" class="form-control" required>
                                    <option value="">Select Level First</option>
                                </select>
                                <small class="form-text text-muted">Choose level to see subjects</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Year <span class="text-danger">*</span></label>
                                <input type="number" name="year" class="form-control" min="1990" max="2030" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Paper Title <span class="text-danger">*</span></label>
                        <input type="text" name="paper_title" class="form-control" placeholder="e.g., Paper 1 - Multiple Choice" required>
                    </div>
                    <div class="form-group">
                        <label>Paper Code</label>
                        <input type="text" name="paper_code" class="form-control" placeholder="e.g., 4024/1">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" style="padding-top: 32px;">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_paid_add" name="is_paid" value="1">
                                    <label class="form-check-label" for="is_paid_add">Paid download</label>
                                </div>
                                <small class="form-text text-muted">Students must pay before downloading this paper.</small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Download Price (MWK)</label>
                                <input type="number" name="price" id="price_add" class="form-control" min="0" step="0.01" placeholder="e.g., 1500" disabled>
                                <small class="form-text text-muted">Set the amount only when paid download is enabled.</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>PDF File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control-file" accept=".pdf" required>
                        <small class="form-text text-muted">Maximum file size: 10MB</small>
                    </div>
                    <div class="form-group">
                        <label>Copyright Notice</label>
                        <textarea name="copyright_notice" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active_add" name="is_active" value="1" checked>
                            <label class="custom-control-label" for="is_active_add">Active (visible to students)</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="addPaperBtn">
                        <i class="fas fa-save mr-2"></i>Add Paper
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Video Solution Modal -->
<div class="modal fade" id="addVideoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-video mr-2"></i>Add Video Solution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addVideoForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div id="addVideoErrors" class="alert alert-danger" style="display:none;"></div>

                    <div class="form-group">
                        <label>Video Platform <span class="text-danger">*</span></label>
                        <select name="video_platform" id="video_platform" class="form-control" required>
                            <option value="">Select Platform</option>
                            <option value="youtube">YouTube</option>
                            <option value="vimeo">Vimeo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Video URL or ID <span class="text-danger">*</span></label>
                        <input type="text" name="video_id" id="video_id" class="form-control" placeholder="e.g., https://youtu.be/dQw4w9WgXcQ or dQw4w9WgXcQ" required>
                        <small class="form-text text-muted">Paste the full URL or just the video ID</small>
                    </div>

                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., MSCE Mathematics 2023 Paper 1 Solution" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief description of the video content"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Curriculum <span class="text-danger">*</span></label>
                                <select name="exam_body" id="video_exam_body" class="form-control" required>
                                    <option value="">Select Curriculum</option>
                                    <?php foreach ($curriculums as $curriculum): ?>
                                        <option value="<?= esc($curriculum) ?>"><?= esc($curriculum) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subject <span class="text-danger">*</span></label>
                                <select name="subject" id="video_subject" class="form-control" required>
                                    <option value="">Select Curriculum First</option>
                                </select>
                                <small class="form-text text-muted">Choose curriculum to see subjects</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Topic</label>
                                <input type="text" name="topic" class="form-control" placeholder="e.g., Quadratic Equations">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Problem Year</label>
                                <input type="number" name="problem_year" class="form-control" min="1990" max="2030" placeholder="2023">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="approved">Approved</option>
                            <option value="pending_review">Pending Review</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="addVideoBtn">
                        <i class="fas fa-save mr-2"></i>Add Video
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Resource Modal -->
<div class="modal fade" id="editResourceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Past Paper</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editResourceForm" action="<?= site_url('admin/library/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="resource_type" value="past_paper">
                <input type="hidden" name="return_url" value="<?= current_url() . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '') ?>">
                <div class="modal-body">
                    <div id="editFormErrors" class="alert alert-danger" style="display:none;"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Curriculum <span class="text-danger">*</span></label>
                                <select name="exam_body" id="edit_exam_body" class="form-control" required>
                                    <option value="">Select Curriculum</option>
                                    <?php foreach ($curriculums as $curriculum): ?>
                                        <option value="<?= esc($curriculum) ?>"><?= esc($curriculum) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Level <span class="text-danger">*</span></label>
                                <select name="exam_level" id="edit_exam_level" class="form-control" required>
                                    <option value="">Select Level</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subject <span class="text-danger">*</span></label>
                                <select name="subject" id="edit_subject" class="form-control" required>
                                    <option value="">Select Subject</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Year <span class="text-danger">*</span></label>
                                <input type="number" name="year" id="edit_year" class="form-control" min="1990" max="2030" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Paper Title <span class="text-danger">*</span></label>
                        <input type="text" name="paper_title" id="edit_paper_title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Paper Code</label>
                        <input type="text" name="paper_code" id="edit_paper_code" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" style="padding-top: 32px;">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="edit_is_paid" name="is_paid" value="1">
                                    <label class="form-check-label" for="edit_is_paid">Paid download</label>
                                </div>
                                <small class="form-text text-muted">Require payment before students can download this paper.</small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Download Price (MWK)</label>
                                <input type="number" name="price" id="edit_price" class="form-control" min="0" step="0.01" placeholder="e.g., 1500" disabled>
                                <small class="form-text text-muted">Use `0` only for free downloads.</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Replace PDF File (Optional)</label>
                        <input type="file" name="file" class="form-control-file" accept=".pdf">
                        <small class="form-text text-muted">Leave empty to keep existing file. Maximum: 10MB</small>
                    </div>
                    <div class="form-group">
                        <label>Copyright Notice</label>
                        <textarea name="copyright_notice" id="edit_copyright_notice" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active_edit" name="is_active" value="1">
                            <label class="custom-control-label" for="is_active_edit">Active (visible to students)</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                        <i class="fas fa-save mr-2"></i>Update Resource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Video Modal -->
<div class="modal fade" id="editVideoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-video mr-2"></i>Edit Video Solution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVideoForm" action="<?= site_url('admin/library/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_video_id">
                <input type="hidden" name="resource_type" value="video">
                <input type="hidden" name="return_url" value="<?= current_url() . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '') ?>">
                <div class="modal-body">
                    <div id="editVideoFormErrors" class="alert alert-danger" style="display:none;"></div>

                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_video_title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Video URL <span class="text-danger">*</span></label>
                        <input type="url" name="video_url" id="edit_video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required>
                        <small class="form-text text-muted">YouTube or Vimeo link</small>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Curriculum <span class="text-danger">*</span></label>
                                <select name="exam_body" id="edit_video_exam_body" class="form-control" required>
                                    <option value="">Select Curriculum</option>
                                    <?php foreach ($curriculums as $curriculum): ?>
                                        <option value="<?= esc($curriculum) ?>"><?= esc($curriculum) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subject <span class="text-danger">*</span></label>
                                <select name="subject" id="edit_video_subject" class="form-control" required>
                                    <option value="">Select Subject</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Topic</label>
                                <input type="text" name="topic" id="edit_video_topic" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Problem Year</label>
                                <input type="number" name="problem_year" id="edit_video_year" class="form-control" min="1990" max="2030">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="edit_video_description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" id="edit_video_status" class="form-control" required>
                            <option value="approved">Approved</option>
                            <option value="pending_review">Pending Review</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editVideoBtn">
                        <i class="fas fa-save mr-2"></i>Update Video
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Helper function to populate dropdown
function populateDropdown(selectElement, options, placeholder) {
    selectElement.innerHTML = '<option value="">' + placeholder + '</option>';
    if (options && options.length > 0) {
        options.forEach(option => {
            const opt = document.createElement('option');
            opt.value = option;
            opt.textContent = option;
            selectElement.appendChild(opt);
        });
        selectElement.disabled = false;
    } else {
        selectElement.disabled = true;
    }
}

// Add Paper Form - Curriculum change
document.getElementById('paper_exam_body').addEventListener('change', function() {
    const curriculum = this.value;
    const levelSelect = document.getElementById('paper_exam_level');
    const subjectSelect = document.getElementById('paper_subject');

    console.log('Curriculum selected:', curriculum);

    // Reset dependent dropdowns
    levelSelect.innerHTML = '<option value="">Loading...</option>';
    levelSelect.disabled = true;
    subjectSelect.innerHTML = '<option value="">Select Level First</option>';
    subjectSelect.disabled = true;

    if (!curriculum) {
        levelSelect.innerHTML = '<option value="">Select Curriculum First</option>';
        return;
    }

    // Fetch levels from server
    const url = '<?= site_url('admin/library/get-levels') ?>';
    const formData = new FormData();
    formData.append('curriculum', curriculum);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    console.log('Fetching levels from:', url);

    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Levels data:', data);
        if (data.success && data.levels) {
            populateDropdown(levelSelect, data.levels, 'Select Level');
        } else {
            levelSelect.innerHTML = '<option value="">No levels found</option>';
            levelSelect.disabled = true;
        }
    })
    .catch(error => {
        console.error('Error fetching levels:', error);
        levelSelect.innerHTML = '<option value="">Error loading levels</option>';
        levelSelect.disabled = true;
    });
});

function updatePaperPricingState(checkboxId, inputId) {
    const checkbox = document.getElementById(checkboxId);
    const input = document.getElementById(inputId);

    if (!checkbox || !input) {
        return;
    }

    input.disabled = !checkbox.checked;
    input.required = checkbox.checked;

    if (!checkbox.checked) {
        input.value = '';
    }
}

document.getElementById('is_paid_add').addEventListener('change', function() {
    updatePaperPricingState('is_paid_add', 'price_add');
});

document.getElementById('edit_is_paid').addEventListener('change', function() {
    updatePaperPricingState('edit_is_paid', 'edit_price');
});

updatePaperPricingState('is_paid_add', 'price_add');
updatePaperPricingState('edit_is_paid', 'edit_price');

// Add Paper Form - Level change
document.getElementById('paper_exam_level').addEventListener('change', function() {
    const curriculum = document.getElementById('paper_exam_body').value;
    const level = this.value;
    const subjectSelect = document.getElementById('paper_subject');

    subjectSelect.innerHTML = '<option value="">Loading...</option>';
    subjectSelect.disabled = true;

    if (!level) {
        subjectSelect.innerHTML = '<option value="">Select Level First</option>';
        return;
    }

    // Fetch subjects from server
    fetch('<?= site_url('admin/library/get-subjects') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'curriculum=' + encodeURIComponent(curriculum) + '&level=' + encodeURIComponent(level) + '&<?= csrf_token() ?>=' + encodeURIComponent('<?= csrf_hash() ?>')
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.subjects) {
            populateDropdown(subjectSelect, data.subjects, 'Select Subject');
        } else {
            subjectSelect.innerHTML = '<option value="">No subjects found</option>';
            subjectSelect.disabled = true;
        }
    })
    .catch(error => {
        console.error('Error fetching subjects:', error);
        subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
        subjectSelect.disabled = true;
    });
});

// Add Video Form - Curriculum change (gets all subjects for that curriculum)
document.getElementById('video_exam_body').addEventListener('change', function() {
    const curriculum = this.value;
    const subjectSelect = document.getElementById('video_subject');

    subjectSelect.innerHTML = '<option value="">Loading...</option>';
    subjectSelect.disabled = true;

    if (!curriculum) {
        subjectSelect.innerHTML = '<option value="">Select Curriculum First</option>';
        return;
    }

    // Fetch all subjects for curriculum (no level filter)
    fetch('<?= site_url('admin/library/get-subjects') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'curriculum=' + encodeURIComponent(curriculum) + '&<?= csrf_token() ?>=' + encodeURIComponent('<?= csrf_hash() ?>')
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.subjects) {
            populateDropdown(subjectSelect, data.subjects, 'Select Subject');
        } else {
            subjectSelect.innerHTML = '<option value="">No subjects found</option>';
            subjectSelect.disabled = true;
        }
    })
    .catch(error => {
        console.error('Error fetching subjects:', error);
        subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
        subjectSelect.disabled = true;
    });
});

// Edit Modal - Curriculum change
document.getElementById('edit_exam_body').addEventListener('change', function() {
    const curriculum = this.value;
    const levelSelect = document.getElementById('edit_exam_level');
    const subjectSelect = document.getElementById('edit_subject');

    levelSelect.innerHTML = '<option value="">Loading...</option>';
    levelSelect.disabled = true;
    subjectSelect.innerHTML = '<option value="">Select Level First</option>';
    subjectSelect.disabled = true;

    if (!curriculum) {
        levelSelect.innerHTML = '<option value="">Select Curriculum First</option>';
        return;
    }

    fetch('<?= site_url('admin/library/get-levels') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'curriculum=' + encodeURIComponent(curriculum) + '&<?= csrf_token() ?>=' + encodeURIComponent('<?= csrf_hash() ?>')
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.levels) {
            populateDropdown(levelSelect, data.levels, 'Select Level');
        }
    });
});

// Edit Modal - Level change
document.getElementById('edit_exam_level').addEventListener('change', function() {
    const curriculum = document.getElementById('edit_exam_body').value;
    const level = this.value;
    const subjectSelect = document.getElementById('edit_subject');

    subjectSelect.innerHTML = '<option value="">Loading...</option>';
    subjectSelect.disabled = true;

    if (!level) {
        subjectSelect.innerHTML = '<option value="">Select Level First</option>';
        return;
    }

    fetch('<?= site_url('admin/library/get-subjects') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'curriculum=' + encodeURIComponent(curriculum) + '&level=' + encodeURIComponent(level) + '&<?= csrf_token() ?>=' + encodeURIComponent('<?= csrf_hash() ?>')
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.subjects) {
            populateDropdown(subjectSelect, data.subjects, 'Select Subject');
        }
    });
});

// Add Past Paper Form
document.getElementById('addPaperForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = document.getElementById('addPaperBtn');
    const originalText = submitBtn.innerHTML;
    const errorDiv = document.getElementById('addPaperErrors');

    errorDiv.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';

    fetch('<?= site_url('admin/library/add') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                throw new Error('Server returned HTML instead of JSON. Check console for details.');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            let errorMsg = data.message || 'Failed to add paper';
            if (data.errors) {
                errorMsg += '<ul class="mb-0 mt-2">';
                for (let key in data.errors) {
                    errorMsg += '<li>' + data.errors[key] + '</li>';
                }
                errorMsg += '</ul>';
            }
            errorDiv.innerHTML = errorMsg;
            errorDiv.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.innerHTML = 'An error occurred: ' + error.message;
        errorDiv.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Add Video Form
document.getElementById('addVideoForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = document.getElementById('addVideoBtn');
    const originalText = submitBtn.innerHTML;
    const errorDiv = document.getElementById('addVideoErrors');

    console.log('Submitting video form...');
    console.log('Form data:', Object.fromEntries(formData));

    errorDiv.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';

    fetch('<?= site_url('admin/library/add-video') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                throw new Error('Server returned HTML instead of JSON. Check console for details.');
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Video response:', data);
        if (data.success) {
            location.reload();
        } else {
            let errorMsg = data.message || 'Failed to add video';
            if (data.errors) {
                errorMsg += '<ul class="mb-0 mt-2">';
                for (let key in data.errors) {
                    errorMsg += '<li>' + data.errors[key] + '</li>';
                }
                errorMsg += '</ul>';
            }
            errorDiv.innerHTML = errorMsg;
            errorDiv.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.innerHTML = 'An error occurred: ' + error.message;
        errorDiv.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Edit Resource - Load data into modal
function editResource(id, type) {
    console.log('Editing resource:', id, type);

    if (type === 'video') {
        editVideo(id);
    } else {
        editPaper(id);
    }
}

function editPaper(id) {
    // Show modal immediately with loading state
    const modal = new bootstrap.Modal(document.getElementById('editResourceModal'));
    modal.show();

    // Reset form
    document.getElementById('edit_id').value = '';
    document.getElementById('edit_year').value = '';
    document.getElementById('edit_paper_title').value = 'Loading...';
    document.getElementById('edit_paper_code').value = '';
    document.getElementById('edit_copyright_notice').value = '';
    document.getElementById('is_active_edit').checked = false;
    document.getElementById('edit_is_paid').checked = false;
    document.getElementById('edit_price').value = '';
    document.getElementById('edit_exam_body').value = '';
    document.getElementById('edit_exam_level').innerHTML = '<option value="">Loading...</option>';
    document.getElementById('edit_exam_level').disabled = true;
    document.getElementById('edit_subject').innerHTML = '<option value="">Loading...</option>';
    document.getElementById('edit_subject').disabled = true;
    updatePaperPricingState('edit_is_paid', 'edit_price');

    // Fetch resource data
    fetch('<?= site_url('admin/library/get/') ?>' + id + '/past_paper', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const resource = data.resource;

            // Set basic fields
            document.getElementById('edit_id').value = resource.id;
            document.querySelector('#editResourceForm input[name="resource_type"]').value = 'past_paper';
            document.getElementById('edit_year').value = resource.year || '';
            document.getElementById('edit_paper_title').value = resource.paper_title || resource.title || '';
            document.getElementById('edit_paper_code').value = resource.paper_code || '';
            document.getElementById('edit_copyright_notice').value = resource.copyright_notice || '';
            document.getElementById('is_active_edit').checked = resource.is_active == 1;
            document.getElementById('edit_is_paid').checked = resource.is_paid == 1;
            document.getElementById('edit_price').value = resource.price || '';
            document.getElementById('edit_exam_body').value = resource.exam_body;
            updatePaperPricingState('edit_is_paid', 'edit_price');

            // Fetch levels
            const curriculum = resource.exam_body;
            if (curriculum) {
                const formData = new FormData();
                formData.append('curriculum', curriculum);

                fetch('<?= site_url('admin/library/get-levels') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(levelData => {
                    const levelSelect = document.getElementById('edit_exam_level');
                    if (levelData.success) {
                        populateDropdown(levelSelect, levelData.levels, 'Select Level');
                        levelSelect.value = resource.exam_level;

                        // Fetch subjects
                        const subjectFormData = new FormData();
                        subjectFormData.append('curriculum', curriculum);
                        subjectFormData.append('level', resource.exam_level);

                        fetch('<?= site_url('admin/library/get-subjects') ?>', {
                            method: 'POST',
                            body: subjectFormData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(subjectData => {
                            const subjectSelect = document.getElementById('edit_subject');
                            if (subjectData.success) {
                                populateDropdown(subjectSelect, subjectData.subjects, 'Select Subject');
                                subjectSelect.value = resource.subject;
                            }
                        })
                        .catch(error => console.error('Subject fetch error:', error));
                    }
                })
                .catch(error => console.error('Level fetch error:', error));
            }
        } else {
            alert('Failed to load resource details: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred: ' + error.message);
    });
}

function editVideo(id) {
    // Show modal immediately with loading state
    const modal = new bootstrap.Modal(document.getElementById('editVideoModal'));
    modal.show();

    // Reset form
    document.getElementById('edit_video_id').value = '';
    document.querySelector('#editVideoForm input[name="resource_type"]').value = 'video';
    document.getElementById('edit_video_title').value = 'Loading...';
    document.getElementById('edit_video_url').value = '';
    document.getElementById('edit_video_topic').value = '';
    document.getElementById('edit_video_year').value = '';
    document.getElementById('edit_video_description').value = '';
    document.getElementById('edit_video_status').value = 'pending_review';
    document.getElementById('edit_video_exam_body').value = '';
    document.getElementById('edit_video_subject').innerHTML = '<option value="">Loading...</option>';
    document.getElementById('edit_video_subject').disabled = true;

    // Fetch resource data
    fetch('<?= site_url('admin/library/get/') ?>' + id + '/video', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Video data:', data);
        if (data.success) {
            const resource = data.resource;

            // Set basic fields
            document.getElementById('edit_video_id').value = resource.id;
            document.querySelector('#editVideoForm input[name="resource_type"]').value = 'video';
            document.getElementById('edit_video_title').value = resource.title || '';

            // Reconstruct video URL from platform and video_id
            let videoUrl = '';
            if (resource.video_platform === 'youtube' && resource.video_id) {
                videoUrl = 'https://www.youtube.com/watch?v=' + resource.video_id;
            } else if (resource.video_platform === 'vimeo' && resource.video_id) {
                videoUrl = 'https://vimeo.com/' + resource.video_id;
            }
            document.getElementById('edit_video_url').value = videoUrl;

            document.getElementById('edit_video_topic').value = resource.topic || '';
            document.getElementById('edit_video_year').value = resource.problem_year || '';
            document.getElementById('edit_video_description').value = resource.description || '';
            document.getElementById('edit_video_status').value = resource.status || 'pending_review';
            document.getElementById('edit_video_exam_body').value = resource.exam_body;

            // Fetch subjects for this curriculum
            const curriculum = resource.exam_body;
            if (curriculum) {
                const formData = new FormData();
                formData.append('curriculum', curriculum);

                fetch('<?= site_url('admin/library/get-subjects') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(subjectData => {
                    const subjectSelect = document.getElementById('edit_video_subject');
                    if (subjectData.success) {
                        populateDropdown(subjectSelect, subjectData.subjects, 'Select Subject');
                        subjectSelect.value = resource.subject;
                    }
                })
                .catch(error => console.error('Subject fetch error:', error));
            }
        } else {
            alert('Failed to load video details: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred: ' + error.message);
    });
}

// Edit Form - Curriculum change handler
document.getElementById('edit_exam_body').addEventListener('change', function() {
    const curriculum = this.value;
    const levelSelect = document.getElementById('edit_exam_level');
    const subjectSelect = document.getElementById('edit_subject');

    levelSelect.innerHTML = '<option value="">Loading...</option>';
    levelSelect.disabled = true;
    subjectSelect.innerHTML = '<option value="">Select Level First</option>';
    subjectSelect.disabled = true;

    if (curriculum) {
        const formData = new FormData();
        formData.append('curriculum', curriculum);

        fetch('<?= site_url('admin/library/get-levels') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateDropdown(levelSelect, data.levels, 'Select Level');
            }
        });
    }
});

// Edit Form - Level change handler
document.getElementById('edit_exam_level').addEventListener('change', function() {
    const curriculum = document.getElementById('edit_exam_body').value;
    const level = this.value;
    const subjectSelect = document.getElementById('edit_subject');

    subjectSelect.innerHTML = '<option value="">Loading...</option>';
    subjectSelect.disabled = true;

    if (curriculum && level) {
        const formData = new FormData();
        formData.append('curriculum', curriculum);
        formData.append('level', level);

        fetch('<?= site_url('admin/library/get-subjects') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateDropdown(subjectSelect, data.subjects, 'Select Subject');
            }
        });
    }
});

// Edit Video - Curriculum change handler
document.getElementById('edit_video_exam_body').addEventListener('change', function() {
    const curriculum = this.value;
    const subjectSelect = document.getElementById('edit_video_subject');

    subjectSelect.innerHTML = '<option value="">Loading...</option>';
    subjectSelect.disabled = true;

    if (curriculum) {
        const formData = new FormData();
        formData.append('curriculum', curriculum);

        fetch('<?= site_url('admin/library/get-subjects') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateDropdown(subjectSelect, data.subjects, 'Select Subject');
            }
        });
    }
});

// Watch Video
function watchVideo(buttonElement) {
    // Get data from button attributes
    const embedCode = buttonElement.getAttribute('data-embed');
    const title = buttonElement.getAttribute('data-title');

    // Create or show video preview modal
    let modalHtml = `
        <div class="modal fade" id="videoWatchModal" tabindex="-1" role="dialog" aria-labelledby="videoWatchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="videoWatchModalLabel">
                            <i class="fas fa-play-circle text-info mr-2"></i>${title || 'Video Preview'}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="embed-responsive embed-responsive-16by9">
                            ${embedCode}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if present
    const existingModal = document.getElementById('videoWatchModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('videoWatchModal'));
    modal.show();

    // Clean up modal when hidden
    document.getElementById('videoWatchModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Approve Video
function approveVideo(id) {
    if (!confirm('Are you sure you want to approve this video? It will become visible to students.')) {
        return;
    }

    fetch('<?= site_url('admin/approve-video/') ?>' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Video approved successfully!');
            window.location.reload();
        } else {
            alert('Failed to approve video: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while approving the video');
    });
}

// Reject Video
function rejectVideo(id) {
    const reason = prompt('Please provide a reason for rejecting this video:');
    if (reason === null) {
        return; // User cancelled
    }

    if (reason.trim() === '') {
        alert('Please provide a reason for rejection.');
        return;
    }

    const formData = new FormData();
    formData.append('reason', reason);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= site_url('admin/reject-video/') ?>' + id, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Video rejected successfully!');
            window.location.reload();
        } else {
            alert('Failed to reject video: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while rejecting the video');
    });
}

// Delete Resource
function deleteResource(id, type) {
    if (!confirm('Are you sure you want to delete this resource?')) {
        return;
    }

    fetch('<?= site_url('admin/library/delete/') ?>' + id + '/' + type, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Failed to delete resource');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>

<style>
.badge {
    padding: 0.4rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
}
.badge-dark {
    background-color: #5a6268;
    color: #fff;
}
.badge-primary {
    background-color: #007bff;
    color: #fff;
}
.table-hover tbody tr:hover {
    background-color: #f8f9fc;
}
.card {
    border: none !important;
}
.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}
.btn-outline-primary:hover {
    color: #fff;
    background-color: #4e73df;
    border-color: #4e73df;
}
.btn-outline-danger:hover {
    color: #fff;
    background-color: #e74a3b;
    border-color: #e74a3b;
}
</style>
<?= $this->endSection() ?>
}

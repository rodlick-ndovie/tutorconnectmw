ALTER TABLE `japan_teaching_applications`
ADD COLUMN `cv_document_path` VARCHAR(255) DEFAULT NULL AFTER `teaching_certificate_path`;

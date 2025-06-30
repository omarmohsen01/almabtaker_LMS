-- Correct Database Update Script: Replace "Rocket LMS" with "MATC SAUDI"
-- Based on actual database structure

-- 1. Update all Rocket LMS references in setting_translations
UPDATE `setting_translations`
SET `value` = REPLACE(`value`, 'Rocket LMS', 'MATC SAUDI')
WHERE `value` LIKE '%Rocket LMS%';

-- 2. Update site email domain references
UPDATE `setting_translations`
SET `value` = REPLACE(`value`, 'mailer@rocket-soft.org', 'mailer@matc-saudi.com')
WHERE `value` LIKE '%rocket-soft.org%';

-- 3. Update any rocket-soft.org domain references
UPDATE `setting_translations`
SET `value` = REPLACE(`value`, 'rocket-soft.org', 'matc-saudi.com')
WHERE `value` LIKE '%rocket-soft.org%';

-- 4. Update "Purchase Rocket LMS" section to "Purchase MATC SAUDI"
UPDATE `setting_translations`
SET `value` = REPLACE(`value`, 'Purchase Rocket LMS', 'Purchase MATC SAUDI')
WHERE `value` LIKE '%Purchase Rocket LMS%';

-- 5. Update any testimonials or other content
UPDATE `testimonial_translations`
SET `comment` = REPLACE(`comment`, 'Rocket LMS', 'MATC SAUDI')
WHERE `comment` LIKE '%Rocket LMS%';

-- 6. Update blog content if exists
UPDATE `blog_translations`
SET `content` = REPLACE(`content`, 'Rocket LMS', 'MATC SAUDI'),
    `description` = REPLACE(`description`, 'Rocket LMS', 'MATC SAUDI')
WHERE `content` LIKE '%Rocket LMS%' OR `description` LIKE '%Rocket LMS%';

-- 7. Update page content if exists
UPDATE `page_translations`
SET `content` = REPLACE(`content`, 'Rocket LMS', 'MATC SAUDI')
WHERE `content` LIKE '%Rocket LMS%';

-- 8. Update FAQ content if exists
UPDATE `faq_translations`
SET `title` = REPLACE(`title`, 'Rocket LMS', 'MATC SAUDI'),
    `answer` = REPLACE(`answer`, 'Rocket LMS', 'MATC SAUDI')
WHERE `title` LIKE '%Rocket LMS%' OR `answer` LIKE '%Rocket LMS%';

-- 9. Update course content if exists
UPDATE `webinar_translations`
SET `title` = REPLACE(`title`, 'Rocket LMS', 'MATC SAUDI'),
    `description` = REPLACE(`description`, 'Rocket LMS', 'MATC SAUDI')
WHERE `title` LIKE '%Rocket LMS%' OR `description` LIKE '%Rocket LMS%';

-- 10. Update any other text content tables
UPDATE `text_lesson_translations`
SET `content` = REPLACE(`content`, 'Rocket LMS', 'MATC SAUDI')
WHERE `content` LIKE '%Rocket LMS%';

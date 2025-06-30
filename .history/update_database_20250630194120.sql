-- Update Database Script: Replace "Rocket LMS" with "MATC SAUDI"
-- Execute these commands in your database

-- 1. Update site name in settings
UPDATE `settings`
SET `value` = REPLACE(`value`, 'Rocket LMS', 'MATC SAUDI')
WHERE `name` = 'site_name';

-- 2. Update setting translations - Replace all Rocket LMS references
UPDATE `setting_translations`
SET `value` = REPLACE(`value`, 'Rocket LMS', 'MATC SAUDI')
WHERE `value` LIKE '%Rocket LMS%';

-- 3. Update specific homepage hero section (English)
UPDATE `setting_translations`
SET `value` = REPLACE(`value`,
    '"description":"Rocket LMS is a fully-featured educational platform that helps instructors to create and publish video courses, live classes, and text courses and earn money, and helps students to learn in the easiest way."',
    '"description":"MATC SAUDI is a fully-featured educational platform that helps instructors to create and publish video courses, live classes, and text courses and earn money, and helps students to learn in the easiest way."'
)
WHERE `setting_id` = 8 AND `locale` = 'en';

-- 4. Update video section description (English)
UPDATE `setting_translations`
SET `value` = REPLACE(`value`,
    '"description":"Use Rocket LMS to access high-quality education materials without any limitations in the easiest way."',
    '"description":"Use MATC SAUDI to access high-quality education materials without any limitations in the easiest way."'
)
WHERE `setting_id` = 27 AND `locale` = 'en';

-- 5. Update club points section (English)
UPDATE `setting_translations`
SET `value` = REPLACE(`value`,
    '"description":"Use Rocket LMS and win club points according to different activities.',
    '"description":"Use MATC SAUDI and win club points according to different activities.'
)
WHERE `value` LIKE '%Use Rocket LMS and win club points%';

-- 6. Update footer About US section (English)
UPDATE `setting_translations`
SET `value` = REPLACE(`value`,
    '"value":"<p><font color=\\"#ffffff\\">Rocket LMS is a fully-featured learning management system that helps you to run your education business in several hours. This platform helps instructors to create professional education materials and',
    '"value":"<p><font color=\\"#ffffff\\">MATC SAUDI is a fully-featured learning management system that helps you to run your education business in several hours. This platform helps instructors to create professional education materials and'
)
WHERE `setting_id` = 4 AND `locale` = 'en';

-- 7. Update Arabic translations
UPDATE `setting_translations`
SET `value` = REPLACE(`value`, 'Rocket LMS', 'MATC SAUDI')
WHERE `locale` = 'ar' AND `value` LIKE '%Rocket LMS%';

-- 8. Update Spanish translations
UPDATE `setting_translations`
SET `value` = REPLACE(`value`, 'Rocket LMS', 'MATC SAUDI')
WHERE `locale` = 'es' AND `value` LIKE '%Rocket LMS%';

-- 9. Update testimonials
UPDATE `testimonial_translations`
SET `comment` = REPLACE(`comment`, 'Rocket LMS', 'MATC SAUDI')
WHERE `comment` LIKE '%Rocket LMS%';

-- 10. Update blog posts (if any)
UPDATE `blog_translations`
SET `content` = REPLACE(`content`, 'Rocket LMS', 'MATC SAUDI'),
    `description` = REPLACE(`description`, 'Rocket LMS', 'MATC SAUDI')
WHERE `content` LIKE '%Rocket LMS%' OR `description` LIKE '%Rocket LMS%';

-- 11. Update pages content
UPDATE `page_translations`
SET `content` = REPLACE(`content`, 'Rocket LMS', 'MATC SAUDI')
WHERE `content` LIKE '%Rocket LMS%';

-- 12. Update site logo path (if stored in database)
UPDATE `settings`
SET `value` = REPLACE(`value`, '/store/1/default_images/logo.png', '/store/1/default_images/matc_logo.png')
WHERE `name` = 'site_logo';

-- 13. Update any remaining references in JSON fields
UPDATE `setting_translations`
SET `value` = REPLACE(`value`, 'rocket-soft.org', 'matc-saudi.com')
WHERE `value` LIKE '%rocket-soft.org%';

-- Clear Laravel cache after database updates (run these as separate commands)
-- These are not SQL commands, run them via terminal after SQL updates

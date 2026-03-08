-- =============================================
-- CLEANUP: Remove duplicate rows from all tables
-- Run this ONCE in phpMyAdmin to remove duplicates
-- =============================================

-- Remove duplicate hotels (keep lowest ID for each name)
DELETE h1 FROM hotels h1
INNER JOIN hotels h2
WHERE h1.id > h2.id AND h1.name = h2.name;

-- Remove duplicate tours (keep lowest ID for each title)
DELETE t1 FROM tours t1
INNER JOIN tours t2
WHERE t1.id > t2.id AND t1.title = t2.title;

-- Remove duplicate destinations (keep lowest ID for each name)
DELETE d1 FROM destinations d1
INNER JOIN destinations d2
WHERE d1.id > d2.id AND d1.name = d2.name;

-- Remove duplicate users (keep lowest ID for each email)
DELETE u1 FROM users u1
INNER JOIN users u2
WHERE u1.id > u2.id AND u1.email = u2.email;

-- Verify counts after cleanup
SELECT 'hotels' AS tbl, COUNT(*) AS cnt FROM hotels
UNION ALL
SELECT 'tours', COUNT(*) FROM tours
UNION ALL
SELECT 'destinations', COUNT(*) FROM destinations
UNION ALL
SELECT 'users', COUNT(*) FROM users;

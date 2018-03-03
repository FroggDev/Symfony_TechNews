-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           5.7.14 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Export de données de la table technewssf.author : ~3 rows (environ)
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` (`id`, `last_name`, `first_name`, `name_slugified`, `email`, `password`, `date_inscription`, `roles`, `last_connexion`) VALUES
	(1, 'Hugo', 'LIEGEARD','hugo-liegeard' , 'wf3@hl-media.fr', 'dGVzdA==', '2018-02-26 11:53:18', 'N;', NULL),
	(2, 'Sylviane', 'PEREZ','sylviane-perez', 'sylviane.perez@wf3.fr', 'dGVzdA==', '2018-02-26 11:53:18', 'N;', NULL),
	(3, 'Froggiz', 'FROGG','froggiz-frogg', 'admin@frogg.fr', 'dGVzdA==', '2018-03-03 18:48:18', 'N;', NULL);

/*!40000 ALTER TABLE `author` ENABLE KEYS */;

-- Export de données de la table technews-rouen.category : ~5 rows (environ)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` (`id`, `label`, `label_slugified`) VALUES
	(1, 'Business','business'),
	(2, 'Computing','computing'),
	(3, 'Tech','tech'),
	(4, 'Politique','politique'),
	(5, 'Social Networking','Social-Networking')
	;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Export de données de la table technews-rouen.article : ~6 rows (environ)
/*!40000 ALTER TABLE `article` DISABLE KEYS */;
INSERT INTO `article` (`id`, `author_id`, `category_id`, `title`, `title_slugified`, `content`, `featured_image`, `special`, `spotlight`, `date_creation`) VALUES
	(1, 1, 2, 'Tip Aligning Digital Marketing with Business Goals and Objectives','tip-aligning-digital-marketing-with-business-goals-and-objectives', ' <p> <span class="dropcap ">N</span>ulla quis lorem ut libero malesuada feugiat. Cras ultricies ligula sed magna dictum porta. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Sed porttitor lectus nibh.</p><p>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Pellentesque in ipsum id orci porta dapibus.</p><div class="post-detail-img"><img alt="" src="http://localhost/POO/technews/public/images/product/4.jpg" /></div><p class="quote">Sed porttitor lectus nibh. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</p><p>Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Pellentesque in ipsum id orci porta dapibus.</p>', '3.jpg', 0, 1, '2018-02-26 09:37:18'),
	(2, 2, 3, 'Six big ways MacOS Sierra is going to change your Apple experience','six-big-ways-macos-sierra-is-going-to-change-your-apple-experience', ' <p> <span class="dropcap ">N</span>ulla quis lorem ut libero malesuada feugiat. Cras ultricies ligula sed magna dictum porta. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Sed porttitor lectus nibh.</p><p>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Pellentesque in ipsum id orci porta dapibus.</p><div class="post-detail-img"><img alt="" src="http://localhost/POO/technews/public/images/product/4.jpg" /></div><p class="quote">Sed porttitor lectus nibh. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</p><p>Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Pellentesque in ipsum id orci porta dapibus.</p>', '4.jpg', 0, 0, '2018-02-26 11:19:18'),
	(3, 2, 2, 'Will Anker be the company to finally put a heads-up display in my car','will-anker-be-the-company-to-finally-put-a-heads-up-display-in-my-car', ' <p> <span class="dropcap ">N</span>ulla quis lorem ut libero malesuada feugiat. Cras ultricies ligula sed magna dictum porta. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Sed porttitor lectus nibh.</p><p>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Pellentesque in ipsum id orci porta dapibus.</p><div class="post-detail-img"><img alt="" src="http://localhost/POO/technews/public/images/product/4.jpg" /></div><p class="quote">Sed porttitor lectus nibh. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</p><p>Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Pellentesque in ipsum id orci porta dapibus.</p>', '5.jpg', 1, 0, '2018-02-26 11:53:18'),
	(4, 1, 3, 'Windows 10 Now Running on 400 Million Active Devices, Says Microsoft','windows-10-now-running-on-400-million-active-devices-says-microsoft', ' <p> <span class="dropcap ">N</span>ulla quis lorem ut libero malesuada feugiat. Cras ultricies ligula sed magna dictum porta. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Sed porttitor lectus nibh.</p><p>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Pellentesque in ipsum id orci porta dapibus.</p><div class="post-detail-img"><img alt="" src="http://localhost/POO/technews/public/images/product/4.jpg" /></div><p class="quote">Sed porttitor lectus nibh. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</p><p>Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Pellentesque in ipsum id orci porta dapibus.</p>', '1.jpg', 0, 0, '2018-02-26 11:53:18'),
	(5, 1, 3, '400 million machines are now running Windows 10','400-million-machines-are-now-running-windows-10', ' <p> <span class="dropcap ">N</span>ulla quis lorem ut libero malesuada feugiat. Cras ultricies ligula sed magna dictum porta. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Sed porttitor lectus nibh.</p><p>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Pellentesque in ipsum id orci porta dapibus.</p><div class="post-detail-img"><img alt="" src="http://localhost/POO/technews/public/images/product/4.jpg" /></div><p class="quote">Sed porttitor lectus nibh. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</p><p>Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Pellentesque in ipsum id orci porta dapibus.</p>', '7.jpg', 0, 1, '2018-02-26 11:53:18'),
	(6, 2, 2, '7 essential lessons from agency marketing to startup growth','7-essential-lessons-from-agency-marketing-to-startup-growth', ' <p> <span class="dropcap ">N</span>ulla quis lorem ut libero malesuada feugiat. Cras ultricies ligula sed magna dictum porta. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Sed porttitor lectus nibh.</p><p>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Pellentesque in ipsum id orci porta dapibus.</p><div class="post-detail-img"><img alt="" src="http://localhost/POO/technews/public/images/product/4.jpg" /></div><p class="quote">Sed porttitor lectus nibh. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</p><p>Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh. Sed porttitor lectus nibh. Pellentesque in ipsum id orci porta dapibus.</p>', '6.jpg', 0, 0, '2018-02-26 11:53:18');
/*!40000 ALTER TABLE `article` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

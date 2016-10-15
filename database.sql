SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------
--
-- 表的结构 `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `summary` mediumtext,
  `tag` varchar(255) DEFAULT NULL,
  `style` varchar(255) DEFAULT NULL,
  `created` bigint(20) unsigned DEFAULT NULL,
  `updated` bigint(20) unsigned DEFAULT NULL,
  `uid` int(10) NOT NULL DEFAULT '0',
  `filetype` varchar(64) NOT NULL DEFAULT 'bcs',
  `filepath` varchar(255) DEFAULT NULL,
  `bid` varchar(50) NOT NULL DEFAULT '0',
  `status` varchar(50) DEFAULT NULL,
  `showall` int(10) NOT NULL DEFAULT '-1',
  `ispublic` tinyint(4) NOT NULL DEFAULT '1',
  `isactive` int(11) NOT NULL DEFAULT '0',
  `ptype` varchar(25) DEFAULT 'full',
  `updateDes` varchar(255) DEFAULT NULL,
  `pictureIncludeBookName` tinyint(2) NOT NULL DEFAULT '0',
  `pictureIncludeBookAuthor` tinyint(2) NOT NULL DEFAULT '0',
  `outlink` varchar(255) DEFAULT NULL,
  `coverpicturelink` varchar(255) DEFAULT NULL,
  `booktype` varchar(255) NOT NULL DEFAULT 'novel',
  `lsize` int(11) DEFAULT '0',
  `outname` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '888888',
  `sex` varchar(50) DEFAULT '',
  `province` varchar(255) DEFAULT '',
  `city` varchar(255) DEFAULT '',
  `created` bigint(20) DEFAULT NULL,
  `updated` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` text,
  `isactive` tinyint(3) DEFAULT '1',
  `age` int(10) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bid` (`bid`),
  ADD KEY `uid` (`uid`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `book`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
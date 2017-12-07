ALTER TABLE `torrent_in_transmission`
	ADD COLUMN `is_delete` CHAR(1) NOT NULL DEFAULT 'N' AFTER `etat_dl_trans`;

ALTER TABLE `files_in_transmission`
	ADD CONSTRAINT `FK_files_in_transmission_torrent_in_transmission` FOREIGN KEY (`id_torrent_in_transmission`) REFERENCES `torrent_in_transmission` (`id`) ON UPDATE RESTRICT ON DELETE CASCADE;
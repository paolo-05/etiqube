CREATE TABLE `scomparti` (
  `id` int(11) NOT NULL,
  `n_sportello` int(11) DEFAULT NULL,
  `n_scheda` int(11) DEFAULT NULL,
  `n_serratura` int(11) DEFAULT NULL,
  `status_serratura` tinyint(1) DEFAULT 0,
  `status_contenuto` tinyint(1) DEFAULT 0,
  `dimensione` varchar(10) NOT NULL DEFAULT '1x',
  `enabled` tinyint(1) DEFAULT 0,
  `scopo` varchar(255) DEFAULT NULL,
  `sync_cloud` int(11) NOT NULL DEFAULT 0,
  `HubUsb` varchar(256) DEFAULT NULL,
  `HubUsbPort` int(2) DEFAULT NULL,
  `gruppo` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `scomparti`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `scomparti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


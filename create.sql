create table `SERVERS` (`id` BIGINT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT, `job_id` VARCHAR(72) NOT NULL UNIQUE, `last_activity` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`))
	

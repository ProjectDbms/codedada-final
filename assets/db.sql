CREATE TABLE IF NOT EXISTS accounts (
    account_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    email_id VARCHAR(255) UNIQUE,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    organizer BOOLEAN DEFAULT FALSE,
    admin BOOLEAN DEFAULT FALSE
);

INSERT INTO accounts(account_id, email_id, username, password, organizer, admin) VALUES(1, 'mguhan439@gmail.com', 'guhan', '$2y$10$aetLd09Z4Cmu/9KiI8UjXuDBvzMN.RL1yQffhHE5i.2OgQnj1QBii', true, true);

CREATE TABLE IF NOT EXISTS contest (
    contest_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    account_id INTEGER,
    contest_name VARCHAR(255),
    description TEXT,
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    difficulty VARCHAR(255),
    CONSTRAINT FK_CONTEST_ACCOUNT FOREIGN KEY(account_id) REFERENCES accounts(account_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS question (
    question_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    contest_id INTEGER,
    question_description TEXT,
    level VARCHAR(255),
    CONSTRAINT FK_QUESTION_CONTEST FOREIGN KEY(contest_id) REFERENCES contest(contest_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS testcase (
	question_id INTEGER,
	testcase_input TEXT,
	testcase_output TEXT,
	points INTEGER,
	CONSTRAINT FK_TESTCASE_QUESTION FOREIGN KEY(question_id) REFERENCES question(question_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE participant(
    participant_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    account_id INTEGER,
    contest_id INTEGER,
    FOREIGN KEY(account_id) REFERENCES accounts(account_id),
    FOREIGN KEY (contest_id) REFERENCES contest(contest_id)
);

CREATE TABLE submission(
    submission_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    question_id INTEGER,
    participant_id INTEGER,
    code_desc TEXT,
    time_submitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(question_id) REFERENCES question(question_id),
    FOREIGN KEY(participant_id) REFERENCES participant(participant_id)
);

CREATE TABLE submission_verdict(
    sub_verdict_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    submission_id INTEGER,
    testcase_output TEXT,
    user_output TEXT,
    points INTEGER,
    FOREIGN KEY(submission_id) REFERENCES submission(submission_id)
);




CREATE TABLE `rank` (
  `rank_id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `rank`
  ADD PRIMARY KEY (`rank_id`),
  ADD KEY `rank_contest` (`contest_id`),
  ADD KEY `rank_participant` (`participant_id`);

ALTER TABLE `rank`
  MODIFY `rank_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `rank`
  ADD CONSTRAINT `rank_contest` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`contest_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rank_participant` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`participant_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;




CREATE TABLE `winner` (
  `winner_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `winner`
  ADD PRIMARY KEY (`winner_id`),
  ADD KEY `winner_account` (`account_id`),
  ADD KEY `winner_contest` (`contest_id`),
  ADD KEY `winner_rank` (`rank_id`);

ALTER TABLE `winner`
  MODIFY `winner_id` int(11) NOT NULL AUTO_INCREMENT;

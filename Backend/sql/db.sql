CREATE TABLE tickets
(
    id          VARCHAR(255) PRIMARY KEY,
    title       VARCHAR(255),
    description TEXT,
    status      ENUM('Open', 'Closed', 'Unassigned', 'Reopened') DEFAULT 'Unassigned',
    priority    ENUM('Low', 'Medium', 'High', 'Urgent') DEFAULT 'Low',
    user_id     VARCHAR(255),
    resolved_at TIMESTAMP,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE users
(
    id       VARCHAR(255) PRIMARY KEY,
    username VARCHAR(255),
    email    VARCHAR(255),
    password VARCHAR(255),
    role     ENUM('Admin', 'User', 'Support_agent') DEFAULT 'User',
    birthday DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    token VARCHAR(255) DEFAULT NULL
);

CREATE TABLE ticket_user
(
    ticket_id VARCHAR(255),
    user_id   VARCHAR(255),
    PRIMARY KEY (ticket_id, user_id),
    FOREIGN KEY (ticket_id) REFERENCES tickets (id),
    FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE ticket_attachment
(
    id         VARCHAR(255) PRIMARY KEY,
    ticket_id  VARCHAR(255),
    filename   VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets (id)
);

CREATE TABLE ticket_message
(
    id         VARCHAR(255) PRIMARY KEY,
    ticket_id  VARCHAR(255),
    user_id    VARCHAR(255),
    message    TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets (id),
    FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE message_attachment
(
    id         VARCHAR(255) PRIMARY KEY,
    message_id VARCHAR(255),
    filename   VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES ticket_message (id)
);

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `birthday`, `created_at`, `updated_at`, `token`) VALUES
('00db564d-416a-4608-997f-33028f70d70c', 'Admin', 'admin@email.com', '$2y$12$gTN7F7XJnT0nZQesNbF0VOpfGJL0CVpqzNyvF5y2dBGteLfJsPaBG', 'Admin', '2025-04-06', '2025-04-06 19:18:54', '2025-04-06 20:49:30', '574bf258-804e-4d59-a296-ac3a9c6b9559'),
('1c8b082d-3c8b-4940-906e-fd06398de39a', 'User', 'user@email.com', '$2y$12$I3jyXBEDGt.XXNoR.dDFu.A5Xr2a7yPjbn2k9Xx4VY6zKoERHjGhG', 'User', '2025-04-06', '2025-04-06 19:18:20', '2025-04-06 20:50:55', '805f5092-74d2-4e5c-85aa-12eb8e855365'),
('6b7555eb-de15-40e4-bc49-24301b364bfe', 'SupportAgent', 'supportagent@email.com', '$2y$12$cMtSXIpFca.dhZIgONxsWOz5/yfgq1kosfqNL411QOiL/43Ero/dG', 'Support_Agent', '2025-04-06', '2025-04-06 19:24:53', '2025-04-06 20:51:32', '8d23c3f7-a701-43e1-84c6-abfc7ca3ab41');

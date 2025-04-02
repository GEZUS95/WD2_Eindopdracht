CREATE TABLE tickets
(
    id          VARCHAR(255) PRIMARY KEY,
    title       VARCHAR(255),
    description TEXT,
    status      ENUM('open', 'closed', 'unassigned', 'reopened') DEFAULT 'unassigned',
    priority    ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'low',
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
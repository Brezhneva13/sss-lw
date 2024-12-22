CREATE TABLE Bank (
    BankNumber INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор банка
    BankName VARCHAR(100) NOT NULL UNIQUE       -- Уникальное имя банка
);

-- Создание таблицы ClientStatus
CREATE TABLE ClientStatus (
    ClientStatusID INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор статуса клиента
    ClientStatusName VARCHAR(50) NOT NULL UNIQUE     -- Уникальное название статуса клиента
);

-- Создание таблицы Client
CREATE TABLE Client (
    ClientNumber INT AUTO_INCREMENT PRIMARY KEY,      -- Уникальный идентификатор клиента
    Phone VARCHAR(20) NOT NULL UNIQUE,                -- Уникальный номер телефона клиента
    Address VARCHAR(100),                              -- Адрес клиента
    CardNumber VARCHAR(20) UNIQUE,                    -- Уникальный номер карты клиента
    Name VARCHAR(50) NOT NULL,                         -- Имя клиента
    Surname VARCHAR(50) NOT NULL,                      -- Фамилия клиента
    Patronymic VARCHAR(50),                            -- Отчество клиента
    BankNumber INT,                                    -- Идентификатор банка
    ClientStatusID INT,                               -- Идентификатор статуса клиента
    FOREIGN KEY (BankNumber) REFERENCES Bank(BankNumber),  -- Внешний ключ на таблицу Bank
    FOREIGN KEY (ClientStatusID) REFERENCES ClientStatus(ClientStatusID)  -- Внешний ключ на таблицу ClientStatus
);

-- Создание таблицы Terminal
CREATE TABLE Terminal (
    TerminalNumber INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор терминала
    BankNumber INT,                                  -- Идентификатор банка
    FOREIGN KEY (BankNumber) REFERENCES Bank(BankNumber)  -- Внешний ключ на таблицу Bank
);


CREATE TABLE Terminal (
TerminalNumber INT AUTO_INCREMENT PRIMARY KEY, -- Уникальный идентификатор терминала
TerminalName VARCHAR(255) NOT NULL, -- Название терминала
BankNumber INT, -- Идентификатор банка
FOREIGN KEY (BankNumber) REFERENCES Bank(BankNumber) ON DELETE SET NULL -- Внешний ключ, с установкой NULL при удалении банка
);

-- Создание таблицы TransactionStatus
CREATE TABLE TransactionStatus (
    TransactionStatusID INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор статуса транзакции
    TransactionStatusName VARCHAR(50) NOT NULL UNIQUE     -- Уникальное название статуса транзакции
);

-- Создание таблицы Transaction
CREATE TABLE Transaction (
    TransactionNumber INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор транзакции
    Date DATE NOT NULL,                                 -- Дата транзакции
    Amount DECIMAL(10,2) NOT NULL,                     -- Сумма транзакции
    ClientNumber INT,                                   -- Идентификатор клиента
    TerminalNumber INT,                                 -- Идентификатор терминала
    TransactionStatusID INT,                            -- Идентификатор статуса транзакции
    FOREIGN KEY (ClientNumber) REFERENCES Client(ClientNumber),  -- Внешний ключ на таблицу Client
    FOREIGN KEY (TerminalNumber) REFERENCES Terminal(TerminalNumber),  -- Внешний ключ на таблицу Terminal
    FOREIGN KEY (TransactionStatusID) REFERENCES TransactionStatus(TransactionStatusID)  -- Внешний ключ на таблицу TransactionStatus
);

-- Создание таблицы Attempt
CREATE TABLE Attempt (
    AttemptNumber INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор попытки
    Date DATE NOT NULL,                             -- Дата попытки
    TransactionNumber INT,                          -- Идентификатор транзакции
    ErrorDescription VARCHAR(255),                  -- Описание ошибки
    ErrorCode VARCHAR(20),                          -- Код ошибки
    FOREIGN KEY (TransactionNumber) REFERENCES Transaction(TransactionNumber)  -- Внешний ключ на таблицу Transaction
);

-- Создание таблицы CardType
CREATE TABLE CardType (
    CardTypeID INT AUTO_INCREMENT PRIMARY KEY,      -- Уникальный идентификатор типа карты
    CardTypeName VARCHAR(50) NOT NULL UNIQUE        -- Уникальное название типа карты
);

-- Создание промежуточной таблицы для связи клиентов и типов карт
CREATE TABLE Client_CardType (
    ClientNumber INT,                                -- Идентификатор клиента
    CardTypeID INT,                                  -- Идентификатор типа карты
    PRIMARY KEY (ClientNumber, CardTypeID),         -- Составной первичный ключ
    FOREIGN KEY (ClientNumber) REFERENCES Client(ClientNumber),  -- Внешний ключ на таблицу Client
    FOREIGN KEY (CardTypeID) REFERENCES CardType(CardTypeID)  -- Внешний ключ на таблицу CardType
);

-- Создание таблицы TransactionInterval
CREATE TABLE TransactionInterval (
    IntervalID INT AUTO_INCREMENT PRIMARY KEY,       -- Уникальный идентификатор интервала транзакции
    IntervalValue INT NOT NULL                       -- Значение интервала
);

-- Создание промежуточной таблицы для связи транзакций и интервалов
CREATE TABLE Transaction_Interval (
    TransactionNumber INT,                           -- Идентификатор транзакции
    IntervalID INT,                                  -- Идентификатор интервала
    PRIMARY KEY (TransactionNumber, IntervalID),    -- Составной первичный ключ
    FOREIGN KEY (TransactionNumber) REFERENCES Transaction(TransactionNumber),  -- Внешний ключ на таблицу Transaction
    FOREIGN KEY (IntervalID) REFERENCES TransactionInterval(IntervalID)  -- Внешний ключ на таблицу TransactionInterval
);

CREATE DATABASE transaction_system;
USE transaction_system;

CREATE TABLE Bank (
    BankNumber INT AUTO_INCREMENT PRIMARY KEY,
    BankName VARCHAR(100) NOT NULL
);

CREATE TABLE Client (
    ClientNumber INT AUTO_INCREMENT PRIMARY KEY,
    Phone VARCHAR(20) NOT NULL,
    Address VARCHAR(100),
    CardNumber VARCHAR(20),
    Name VARCHAR(50) NOT NULL,
    Surname VARCHAR(50) NOT NULL,
    Patronymic VARCHAR(50),
    BankNumber INT,
    FOREIGN KEY (BankNumber) REFERENCES Bank(BankNumber)
);

CREATE TABLE Terminal (
    TerminalNumber INT AUTO_INCREMENT PRIMARY KEY,
    BankNumber INT,
    FOREIGN KEY (BankNumber) REFERENCES Bank(BankNumber)
);

CREATE TABLE Transaction (
    TransactionNumber INT AUTO_INCREMENT PRIMARY KEY,
    Date DATE NOT NULL,
    Amount DECIMAL(10,2) NOT NULL,
    ClientNumber INT,
    TerminalNumber INT,
    FOREIGN KEY (ClientNumber) REFERENCES Client(ClientNumber),
    FOREIGN KEY (TerminalNumber) REFERENCES Terminal(TerminalNumber)
);

CREATE TABLE Attempt (
    AttemptNumber INT AUTO_INCREMENT PRIMARY KEY,
    Date DATE NOT NULL,
    TransactionNumber INT,
    ErrorDescription VARCHAR(255),
    ErrorCode VARCHAR(20),
    FOREIGN KEY (TransactionNumber) REFERENCES Transaction(TransactionNumber)
);

CREATE TABLE ClientStatus (
    ClientStatusID INT AUTO_INCREMENT PRIMARY KEY,
    ClientStatusName VARCHAR(50) NOT NULL
);

CREATE TABLE CardType (
    CardTypeID INT AUTO_INCREMENT PRIMARY KEY,
    CardTypeName VARCHAR(50) NOT NULL
);

CREATE TABLE TransactionStatus (
    TransactionStatusID INT AUTO_INCREMENT PRIMARY KEY,
    TransactionStatusName VARCHAR(50) NOT NULL
);

CREATE TABLE Client_CardType (
    ClientNumber INT,
    CardTypeID INT,
    PRIMARY KEY (ClientNumber, CardTypeID),
    FOREIGN KEY (ClientNumber) REFERENCES Client(ClientNumber),
    FOREIGN KEY (CardTypeID) REFERENCES CardType(CardTypeID)
);

CREATE TABLE Transaction_TransactionStatus (
    TransactionNumber INT,
    TransactionStatusID INT,
    PRIMARY KEY (TransactionNumber, TransactionStatusID),
    FOREIGN KEY (TransactionNumber) REFERENCES Transaction(TransactionNumber),
    FOREIGN KEY (TransactionStatusID) REFERENCES TransactionStatus(TransactionStatusID)
);

CREATE TABLE Interval (
    IntervalID INT AUTO_INCREMENT PRIMARY KEY,
    IntervalValue INT NOT NULL
);

CREATE TABLE Transaction_Interval (
    TransactionNumber INT,
    IntervalID INT,
    PRIMARY KEY (TransactionNumber, IntervalID),
    FOREIGN KEY (TransactionNumber) REFERENCES Transaction(TransactionNumber),
    FOREIGN KEY (IntervalID) REFERENCES Interval(IntervalID)
);

-- Добавление ограничений для статуса клиента 
ALTER TABLE Client
ADD Status VARCHAR(50) NOT NULL,
ADD CONSTRAINT CK_ClientStatus CHECK (Status IN ('Активный', 'Задолженность'));

-- Добавление ограничений для статуса транзакции 
ALTER TABLE Transaction
ADD Status VARCHAR(50) NOT NULL,
ADD CONSTRAINT CK_TransactionStatus CHECK (Status IN ('В процессе обработки', 'Успешно', 'Отказано'));

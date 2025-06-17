CREATE TABLE hotel(
id INT PRIMARY KEY,
NAME VARCHAR(30),
Location VARCHAR(30));

INSERT INTO hotel(id,NAME,Location) VALUES(1, 'Hotel Everest', 'Kathmandu'),
(2, 'Himalayan Retreat', 'Pokhara'),
(3, 'Lumbini Heritage', 'Lumbini'),
(4, 'Chitwan Safari Lodge', 'Chitwan'),
(5, 'Nagarkot Hill Resort', 'Nagarkot'),
(6, 'Bhaktapur Palace Hotel', 'Bhaktapur'),
(7, 'Lakeside Inn', 'Pokhara'),
(8, 'Patan Boutique Hotel', 'Patan'),
(9, 'Dhulikhel Mountain Resort', 'Dhulikhel'),
(10, 'Janakpur Royal Hotel', 'Janakpur');


CREATE TABLE Administration(
id INT PRIMARY KEY,
Post VARCHAR(20),
Email VARCHAR(30),
Phone VARCHAR(20));
 
INSERT INTO administration (id, post, email, phone) VALUES
(1, 'Hotel Manager', 'manager@example.com', '9841000000'),
(2, 'Front Desk Manager', 'frontdesk@example.com', '98411111'),
(3, 'Finance Manager', 'finance@example.com', '9841222222'),
(4, 'HR Manager', 'hrmanager@example.com', '9841333333'),
(5, 'Operations Manager', 'operations@example.com', '9841444444'),
(6, 'Sales Manager', 'salesmanager@example.com', '9841555555'),
(7, 'Housekeeping Manager', 'housekeeping@example.com', '9841666666'),
(8, 'Maintenance Manager', 'maintenance@example.com', '9841777777'),
(9, 'Food and Beverage Manager', 'fnbmanager@example.com', '9841888888'),
(10, 'Security Manager', 'security@example.com', '9841999999');

ALTER TABLE administration ADD COLUMN hotel_id INT;
ALTER TABLE administration ADD CONSTRAINT hotel_id FOREIGN KEY (hotel_id) REFERENCES hotel(id);

 
 CREATE TABLE Customer(
 c_id INT PRIMARY KEY,
 NAME VARCHAR(20),
 Address VARCHAR(20),
 Mobile_no VARCHAR(20));

INSERT INTO Customer (c_id, NAME, Address, Mobile_no) VALUES
(1, 'Ramesh Pokharel', 'Kathmandu, Nepal', '984112345'),
(2, 'Sita Shrestha', 'Pokhara, Nepal', '9841234567'),
(3, 'Bikash Thapa', 'Bhaktapur, Nepal', '9841345678'),
(4, 'Anjali Maharjan', 'Lalitpur, Nepal', '9841456789'),
(5, 'Raju Karki', 'Chitwan, Nepal', '984156789'),
(6, 'Pooja Joshi', 'Biratnagar, Nepal', '984167890'),
(7, 'Ashok Lama', 'Dharan, Nepal', '9841789012'),
(8, 'Meena Gurung', 'Butwal, Nepal', '9841890123'),
(9, 'Prakash Acharya', 'Nepalgunj, Nepal', '984190123'),
(10, 'Laxmi Poudel', 'Hetauda, Nepal', '9841012345');

 ALTER TABLE Customer ADD CONSTRAINT mobile_no UNIQUE (Mobile_no);



 CREATE TABLE room (
    room_no INT PRIMARY KEY,
    type VARCHAR(50),
    total_people INT
);

INSERT INTO room (room_no, type, total_people) VALUES
(1, 'Single', 1),
(2, 'Single', 1),
(3, 'Single', 1),
(4, 'Double', 2),
(5, 'Double', 2),
(6, 'Double', 2),
(7, 'Suite', 4),
(8, 'Suite', 4),
(9, 'Suite', 4),
(10, 'Suite', 4);

CREATE TABLE room_type (
    room_code INT PRIMARY KEY,
    payment_type VARCHAR(50),
    name VARCHAR(50)
);

INSERT INTO room_type (room_code, payment_type, name) VALUES
(1, 'Cash', 'Standard Single'),
(2, 'Cash', 'Standard Double'),
(3, 'Credit Card', 'Deluxe Single'),
(4, 'Credit Card', 'Deluxe Double'),
(5, 'Cash', 'Suite A'),
(6, 'Credit Card', 'Suite B'),
(7, 'Cash', 'Family Room'),
(8, 'Cash', 'Presidential Suite'),
(9, 'Credit Card', 'Executive Suite'),
(10, 'Credit Card', 'Luxury Suite');

 CREATE TABLE transaction (
    entry_date DATE,
    exit_date DATE,
    total_amt DECIMAL(10, 2),
    record_id INT PRIMARY KEY
);

INSERT INTO transaction (entry_date, exit_date, total_amt, record_id) VALUES
('2024-05-01', '2024-05-03', 5000.00, 1),
('2024-05-02', '2024-05-04', 7500.00, 2),
('2024-05-05', '2024-05-08', 10000.00, 3),
('2024-05-07', '2024-05-10', 8500.00, 4),
('2024-05-09', '2024-05-12', 12000.00, 5),
('2024-05-11', '2024-05-14', 9000.00, 6),
('2024-05-15', '2024-05-17', 6000.00, 7),
('2024-05-18', '2024-05-20', 8000.00, 8),
('2024-05-21', '2024-05-25', 11000.00, 9),
('2024-05-22', '2024-05-27', 13000.00, 10);

ALTER TABLE transaction ADD COLUMN customer_id INT;
ALTER TABLE transaction ADD CONSTRAINT customer_id FOREIGN KEY (customer_id) REFERENCES Customer(c_id);


CREATE TABLE payment_type (
    sn INT PRIMARY KEY,
    cash VARCHAR(50),
    cheque VARCHAR(50),
    electronic VARCHAR(50)
);

INSERT INTO payment_type (sn, cash, cheque, electronic) VALUES
(1, 'Cash', 'No', 'Yes'),
(2, 'Cash', 'Yes', 'No'),
(3, 'Cash', 'Yes', 'Yes'),
(4, 'Cheque', 'Yes', 'No'),
(5, 'Cheque', 'Yes', 'Yes'),
(6, 'Electronic', 'No', 'Yes'),
(7, 'Electronic', 'Yes', 'No'),
(8, 'Electronic', 'Yes', 'Yes');

--innerjoin
SELECT c.*, t.entry_date, t.exit_date, t.total_amt
FROM Customer c
INNER JOIN transaction t ON c.c_id = t.customer_id;

--leftjoin
SELECT c.*, t.entry_date, t.exit_date, t.total_amt
FROM Customer c
LEFT JOIN transaction t ON c.c_id = t.customer_id;

--rightjoin
SELECT c.*, t.entry_date, t.exit_date, t.total_amt
FROM Customer c
RIGHT JOIN transaction t ON c.c_id = t.customer_id;

--subquery in select for aggerate information retrival
SELECT c.*, 
       (SELECT COUNT(*) FROM transaction WHERE customer_id = c.c_id) AS transaction_count
FROM Customer c;

--subquery in where for filter of row on conditions
SELECT *
FROM Customer
WHERE c_id IN (SELECT DISTINCT customer_id FROM transaction WHERE total_amt > 10000);

--subquery in from using join
SELECT t.customer_id, c.NAME, t.total_amt
FROM (SELECT customer_id, SUM(total_amt) AS total_amt FROM transaction GROUP BY customer_id) AS t
INNER JOIN Customer c ON c.c_id = t.customer_id;

--for insertion
INSERT INTO transaction (entry_date, exit_date, total_amt, customer_id)
SELECT '2024-05-01', '2024-05-03', 5000.00, c_id
FROM Customer
WHERE NAME = 'Ramesh Pokharel';

-UPDATE Customer
SET Address = 'New Address, Kathmandu, Nepal', Mobile_no = '9841999999'
WHERE c_id = 1;

DELETE FROM Customer
WHERE c_id = 5;

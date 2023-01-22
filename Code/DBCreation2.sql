CREATE
DATABASE	MetricuL8v2;

USE	MetricuL8v2;

CREATE
TABLE	`student`
	(`id` int(11) NOT NULL COMMENT 'Student ID',
	`email` varchar(1000) NOT NULL COMMENT 'Student Email',
	`password` varchar(256) NOT NULL COMMENT 'Password',
	`firstName` varchar(100) NOT NULL COMMENT 'First Name',
	`lastName` varchar(100) NOT NULL COMMENT 'Last Name',
	`phoneArea` char(3) NOT NULL COMMENT 'Phone Area Code',
	`phoneNumber` char(7) NOT NULL COMMENT 'Phone Number',
    `birthDate` date NOT NULL COMMENT 'Date of Birth',
	PRIMARY KEY (`id`),
    CONSTRAINT uq_email UNIQUE(`email`)
) COMMENT='Student Table';

INSERT INTO student (`id`, `email`, `password`, `firstname`, `lastname`, `phonearea`, `phonenumber`,  `birthdate`)  
VALUES(0, 'admin@metricul8.com', 'admin99999', 'admin', 'blank', 888, 5551212, '1900-01-01');

CREATE
TABLE	`class`
	(`id` int(11) not null comment 'Class ID',
    `classDescription` varchar(100) not null comment 'Class Description',
    `year` int(11) not null comment 'Class Year',
    `semester` int(11) not null comment 'Class Semester',
    `maxEnrollment` int(11) not null comment 'Maximum Class Enrollment',
    PRIMARY KEY (`id`, `year`, `semester`)
) COMMENT='Class Table' ;

INSERT INTO class (`id`, `classDescription`, `year`, `semester`, `maxEnrollment`)  
VALUES(0, 'adminClass', 2000, 1, 1);

CREATE
TABLE `enrollment`
	(`id` int(11) not null comment 'Enrollment ID',
    `studentId` int(11) not null comment 'Student ID',
    `classId` int(11) not null comment 'Class ID',
    PRIMARY KEY (`id`),
    CONSTRAINT uq_student_class UNIQUE(`studentId`, `classId`),
    CONSTRAINT fk_enrollment_class FOREIGN KEY (`classId`) REFERENCES `class`(`id`),
    CONSTRAINT fk_enrommnent_student FOREIGN KEY (`studentId`) REFERENCES `student`(`id`)
) COMMENT='Enrollment Table' ;

INSERT INTO enrollment (`id`, `studentId`, `classId`)
VALUES(0, 0, 0) ;

CREATE
TABLE	`waitList`
	(`id` int(11) not null comment 'Wait List ID',
    `classId` int(11) not null comment 'Class ID',
    `studentId` int(11) not null comment 'Student ID',
    `waitingSince` timestamp not null comment 'Waiting Since',
    PRIMARY KEY (`id`),
    CONSTRAINT uq_class_student UNIQUE(`classId`,`studentId`),
    CONSTRAINT fk_waitList_class FOREIGN KEY (`classId`) REFERENCES `class`(`id`),
    CONSTRAINT fk_waitlist_student FOREIGN KEY (`studentId`) REFERENCES `student`(`id`)) ;
    
INSERT INTO waitList (`id`, `classId`, `studentId`, `waitingSince`)
VALUES(0, 0, 0, CURRENT_TIMESTAMP()) ;

DELIMITER //

CREATE
PROCEDURE	enrollStudent (IN classId INT, IN studentId INT)
BEGIN
	DECLARE cSize, 
			maxSize,
            nextId            
		INT DEFAULT 0;
    
    SELECT	cSize = COUNT(*)
    FROM	enrollment
    WHERE		enrollment.classId = classId;
    
    SELECT	maxSize = class.maxEnrollment
    FROM	class
    WHERE		class.id	=	classId;
    
    IF ( cSize <= maxSize ) THEN
    
		SELECT nextId = COUNT(*)
        FROM	enrollment;
        
        IF ( nextId = 0 ) THEN 
			SET nextId = 1;
		ELSE
			SET nextId = 0;        
			SELECT	nextId = MAX(id)+1
            FROM	enrollment;
        END IF;
        
        INSERT
        INTO	enrollment
        VALUES	(nextId, studentId, classId);
        
        SELECT	'A' AS addResult;
    ELSE
		SET		cSize = 0;
		SELECT	cSize = COUNT(*)
        FROM	waitlist
        WHERE		waitlist.classId	=	classId
				AND	waitlist.studentId	=	studentId;
                
		IF (cSize = 0) THEN 
        
			SET		nextId = 0;
			SELECT	nextId = COUNT(*)
			FROM	waitlist;
			
			IF ( nextId = 0 ) THEN 
				SET nextId = 1;
			ELSE
				SET nextId = 0;        
				SELECT	nextId = MAX(id)+1
				FROM	waitlist;
			END IF;
        
			INSERT
            INTO	waitlist
            VALUES	(nextId, classId, studentId, CURRENT_TIMESTAMP());
            
		END IF;
        
        SELECT	 'W' AS addResult;
	END IF;
END//

CREATE
PROCEDURE	removeStudent (IN enrollmentId INT)
BEGIN
	DELETE FROM enrollment WHERE id = enrollmentId;
END//

DELIMITER ;
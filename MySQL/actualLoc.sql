DELIMITER |
DROP PROCEDURE IF EXISTS actualLoc;
CREATE PROCEDURE actualLoc(nodeA varchar(100),nodeB varchar(100))
BEGIN

DECLARE parent1 varchar(100);
DECLARE parent2 varchar(100);
DECLARE temp varchar(100);
DECLARE lca varchar(100);
DECLARE output varchar(1000);
DECLARE nodeName1 varchar(100);
DECLARE temp1 varchar(1000);
DECLARE flag integer;
DECLARE nodeName2 varchar(100);
DECLARE tempNode varchar(100);
DECLARE testNode varchar(100);
DECLARE totalNodes INTEGER;

SET parent1=nodeA;
SET parent2=nodeB;
SET flag=0;
SET output='';
SET lca='';
SET temp1='';
/*
NEW NODES CODING*/
DROP TEMPORARY TABLE IF EXISTS LIST_UP_NODES;
CREATE TEMPORARY TABLE LIST_UP_NODES(name varchar(100));

SET tempNode=(SELECT nodeName from NODE_USER where nodeName=nodeA and userName=nodeB);
SET testNode=(SELECT userName from NODE_USER where userName=nodeA limit 1);
IF tempNode is NULL
THEN
IF testNode is not NULL
THEN
/*Case where both nodes are users*/
loop1: WHILE parent1<>'null' or parent1 is not NULL
DO
/*A.userName=1*/
SET nodeName1=(SELECT A.nodeName from NODE_USER A JOIN NODE_USER B where A.userName=nodeA and A.nodeName=parent1 and B.nodeName=A.nodeName and B.userName=nodeB);
INSERT INTO LIST_UP_NODES values(parent1);
IF nodeName1 is NOT NULL /*and nodeName1<>nodeA*/
THEN
leave loop1;
END IF;
SET parent1=(SELECT parent from newNodes where name=parent1);
END WHILE;

SET nodeName2=nodeName1;

INSERT INTO LIST_UP_NODES values(nodeB);

SELECT GROUP_CONCAT(name) as name from LIST_UP_NODES;
ELSE

/*Case where one node is a node and another is a user and the user is not in the subtree of node*/
loop1: WHILE parent1<>'null' or parent1 is not NULL
DO
SET nodeName1=(select name from newNodes A join NODE_USER B where A.Name=b.NodeName and B.userName=nodeB and A.Name=parent1);
INSERT INTO LIST_UP_NODES values(parent1);
IF nodeName1 is NOT NULL /*and nodeName1<>nodeA*/
THEN
leave loop1;
END IF;
SET parent1=(SELECT parent from newNodes where name=parent1);
END WHILE;

INSERT INTO LIST_UP_NODES values(nodeB);

SELECT GROUP_CONCAT(name) as name from LIST_UP_NODES;

END IF;


ELSE
INSERT INTO LIST_UP_NODES values(nodeB);
SELECT GROUP_CONCAT(name) as name from LIST_UP_NODES;

END IF;

SET totalNodes=(SELECT COUNT(*) from LIST_UP_NODES);
UPDATE params_tbl SET AcLoS=AcLoS+totalNodes;

END |
DELIMITER ;
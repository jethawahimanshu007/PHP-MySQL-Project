DELIMITER |
DROP PROCEDURE IF EXISTS searchPart;
CREATE PROCEDURE searchPart(NodeA varchar(100),NodeB varchar(100))
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
DECLARE partitionNodes varchar(1000);
DECLARE finalNode varchar(100);
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
loop2: WHILE nodeName2<>nodeB
DO


SET partitionNodes=(select GROUP_CONCAT(A.NodeName) as name from partition_tbl A JOIN node_user B ON B.nodeName=A.partName and B.userName=NodeB and B.nodeName=nodeName2);


IF partitionNodes is NULL
THEN
SET nodeName2=(SELECT A.name from newNodes A JOIN NODE_USER B where parent=nodeName2 and A.name=B.nodeName and B.username=nodeB);
INSERT INTO LIST_UP_NODES values(nodeName2);
ELSE

INSERT INTO LIST_UP_NODES values(partitionNodes);
SET finalNode=(select A.NodeName from partition_tbl A JOIN node_user B JOIN newNodes C ON B.nodeName=A.partName and B.userName=NodeB and B.nodeName=nodeName2 AND C.name=B.userName and C.parent=A.NodeName);
INSERT LIST_UP_NODES VALUES(finalNode);
INSERT INTO LIST_UP_NODES VALUES(NodeB);
leave loop2;
END IF;

END WHILE;
SELECT GROUP_CONCAT(name SEPARATOR ';') as name from LIST_UP_NODES;


ELSE
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

SET nodeName2=nodeName1;
loop2: WHILE nodeName2<>nodeB
DO


SET partitionNodes=(select GROUP_CONCAT(A.NodeName) as name from partition_tbl A JOIN node_user B ON B.nodeName=A.partName and B.userName=NodeB and B.nodeName=nodeName2);

IF partitionNodes is NULL
THEN
SET nodeName2=(SELECT A.name from newNodes A JOIN NODE_USER B where parent=nodeName2 and A.name=B.nodeName and B.username=nodeB);
INSERT INTO LIST_UP_NODES values(nodeName2);
ELSE

INSERT INTO LIST_UP_NODES values(partitionNodes);
SET finalNode=(select A.NodeName from partition_tbl A JOIN node_user B JOIN newNodes C ON B.nodeName=A.partName and B.userName=NodeB and B.nodeName=nodeName2 AND C.name=B.userName and C.parent=A.NodeName);
INSERT LIST_UP_NODES VALUES(finalNode);
INSERT INTO LIST_UP_NODES VALUES(NodeB);
leave loop2;
END IF;

END WHILE;
SELECT GROUP_CONCAT(name SEPARATOR ';') as name from LIST_UP_NODES;

END IF;

ELSE

SET nodeName2=nodeA;
INSERT INTO LIST_UP_NODES values(nodeName2);


loop2: WHILE nodeName2<>nodeB
DO

SET partitionNodes=(select GROUP_CONCAT(A.NodeName) as name from partition_tbl A JOIN node_user B ON B.nodeName=A.partName and B.userName=NodeB and B.nodeName=nodeName2);


IF partitionNodes is NULL
THEN
SET nodeName2=(SELECT A.name from newNodes A JOIN NODE_USER B where parent=nodeName2 and A.name=B.nodeName and B.username=nodeB);
INSERT INTO LIST_UP_NODES values(nodeName2);
ELSE

INSERT INTO LIST_UP_NODES values(partitionNodes);
SET finalNode=(select A.NodeName from partition_tbl A JOIN node_user B JOIN newNodes C ON B.nodeName=A.partName and B.userName=NodeB and B.nodeName=nodeName2 AND C.name=B.userName and C.parent=A.NodeName);
INSERT LIST_UP_NODES VALUES(finalNode);
INSERT INTO LIST_UP_NODES VALUES(NodeB);
leave loop2;
END IF;

END WHILE;
SELECT GROUP_CONCAT(name SEPARATOR ';') as name from LIST_UP_NODES;
END IF;

	SET totalNodes=(SELECT COUNT(*) from LIST_UP_NODES);
	UPDATE params_tbl SET ParS=ParS+totalNodes;

END |
DELIMITER ;
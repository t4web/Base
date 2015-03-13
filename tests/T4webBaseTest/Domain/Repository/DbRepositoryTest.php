<?php

namespace T4webBaseTest\Domain\Repository;

use T4webBase\Domain\Repository\DbRepository;

class DbRepositoryTest extends \PHPUnit_Framework_TestCase {
    
    private $DbRepository;
    private $dbMapperMock;
    private $tableGatewayMock;
    private $queryBuilderMock;

    public function setUp() {
        $this->dbMapperMock = $this->getMock('T4webBase\Domain\Mapper\DbMapperInterface');
        $this->queryBuilderMock = $this->getMock('T4webBase\Db\QueryBuilderInterface');
        $this->tableGatewayMock = $this->getMock('T4webBase\Db\TableGatewayInterface');
        
        $this->DbRepository = new DbRepository(
                $this->tableGatewayMock,
                $this->dbMapperMock,
                $this->queryBuilderMock);
    }
    
    /**
     * @dataProvider providerFindMany
     */
    public function testFindMany($rows) {
        
        $criteriaMock = $this->getMock('T4webBase\Domain\Criteria\CriteriaInterface');
        $criteriaMock->expects($this->once())
                ->method('build')
                ->with($this->identicalTo($this->queryBuilderMock));
        
        $selectMock = $this->getMockBuilder('T4webBase\Db\Select')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->queryBuilderMock->expects($this->once())
                ->method('getQuery')
                ->will($this->returnValue($selectMock));

        $this->tableGatewayMock->expects($this->once())
                ->method('selectMany')
                ->with($this->equalTo($selectMock))
                ->will($this->returnValue($rows));
        
        $expectedCollection =  new \T4webBase\Domain\Collection();
        
        $entity1 = new \T4webBase\Domain\Entity($rows[0]);
        $expectedCollection->offsetSet($rows[0]['id'], $entity1);
        
        $entity2 = new \T4webBase\Domain\Entity($rows[1]);
        $expectedCollection->offsetSet($rows[1]['id'], $entity2);
        
        $this->dbMapperMock->expects($this->once())
                ->method('fromTableRows')
                ->with($this->equalTo($rows))
                ->will($this->returnValue($expectedCollection));
        
        $identityMap =  new \T4webBase\Domain\Repository\IdentityMap();
        $this->DbRepository->setIdentityMap($identityMap);
        
        $actualCollection = $this->DbRepository->findMany($criteriaMock);
        
        $this->assertSame($expectedCollection, $actualCollection);
        $this->assertSame($entity1, $identityMap[$rows[0]['id']]);
        $this->assertSame($entity2, $identityMap[$rows[1]['id']]);
    }
    
    public function providerFindMany() {
        return array(
            array(array(array('id' => 1, 'foo' => 'bar'), array('id' => 2, 'foo' => 'bar'))),
            array(array(array('id' => 1, 'foo2' => 'bar'), array('id' => 2, 'foo3' => 'bar')))
        );
    }
    
    public function testAddNewEntity() {
        $id = null;
        $entity = new \T4webBase\Domain\Entity();
        $identityMap =  new \T4webBase\Domain\Repository\IdentityMap();
        
        $this->DbRepository->setIdentityMap($identityMap);
        
        $row = array(
            'id' => $id,
            'foo' => 'bar'
        );
        $this->dbMapperMock->expects($this->once())
                ->method('toTableRow')
                ->with($this->equalTo($entity))
                ->will($this->returnValue($row));
        
        $this->tableGatewayMock->expects($this->once())
                ->method('insert')
                ->with($this->equalTo($row));
        
        $lastInsertId = 111;
        $this->tableGatewayMock->expects($this->once())
                ->method('getLastInsertId')
                ->will($this->returnValue($lastInsertId));
        
        $this->DbRepository->add($entity);
        
        $this->assertSame($entity, $identityMap[$lastInsertId]);
    }
    
    public function testAddExistingEntity() {
        $id = 111;
        $entityMock = $this->getMock('T4webBase\Domain\Entity');
        $entityMock->expects($this->at(0))
                ->method('getId')
                ->will($this->returnValue($id));
        
        $identityMapMock = $this->getMock('T4webBase\Domain\Repository\IdentityMap');
        $identityMapMock->expects($this->once())
                ->method('offsetExists')
                ->with($id)
                ->will($this->returnValue(true));
        $this->DbRepository->setIdentityMap($identityMapMock);
        
        $row = array(
            'id' => $id,
            'foo' => 'bar'
        );
        $this->dbMapperMock->expects($this->once())
                ->method('toTableRow')
                ->with($this->equalTo($entityMock))
                ->will($this->returnValue($row));
        
        $this->tableGatewayMock->expects($this->never())
                ->method('insert');
        
        $this->tableGatewayMock->expects($this->once())
                ->method('updateByPrimaryKey')
                ->with(
                    $this->equalTo($row),
                    $this->equalTo($id)
                );
        $identityMapMock->expects($this->never())
                ->method('offsetSet');
        
        $this->DbRepository->add($entityMock);
    }
    
    public function testAddNewEntityWithId() {
        $id = 111;
        $entity = new \T4webBase\Domain\Entity(array('id' => $id));
        
        $identityMapMock = $this->getMock('T4webBase\Domain\Repository\IdentityMap');
        $identityMapMock->expects($this->once())
                ->method('offsetExists')
                ->with($id)
                ->will($this->returnValue(false));
        $this->DbRepository->setIdentityMap($identityMapMock);
        
        $row = array(
            'id' => $id,
            'foo' => 'bar'
        );
        $this->dbMapperMock->expects($this->once())
                ->method('toTableRow')
                ->with($this->equalTo($entity))
                ->will($this->returnValue($row));
        
        $this->tableGatewayMock->expects($this->once())
                ->method('insert')
                ->with($this->equalTo($row));
        
        $this->tableGatewayMock->expects($this->never())
                ->method('getLastInsertId');
        
        $identityMapMock->expects($this->once())
                ->method('offsetSet')
                ->with($id, $entity);
        
        $this->DbRepository->add($entity);
    }
    
    public function testFind() {
        $criteriaMock = $this->getMock('T4webBase\Domain\Criteria\CriteriaInterface');
        $criteriaMock->expects($this->once())
                ->method('build')
                ->with($this->identicalTo($this->queryBuilderMock));
        
        $selectMock = $this->getMockBuilder('T4webBase\Db\Select')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->queryBuilderMock->expects($this->once())
                ->method('getQuery')
                ->will($this->returnValue($selectMock));
        
        $row = array('foo' => 'bar');
        
        $this->tableGatewayMock->expects($this->once())
                ->method('selectOne')
                ->with($this->equalTo($selectMock))
                ->will($this->returnValue($row));
        
        $expectedEntityMock = $this->getMock('T4webBase\Domain\EntityInterface');
        $expectedEntityMock->expects($this->once())
                ->method('getId')
                ->will($this->returnValue(111));
        
        $this->dbMapperMock->expects($this->once())
                ->method('fromTableRow')
                ->with($this->equalTo($row))
                ->will($this->returnValue($expectedEntityMock));
        
        $identityMapMock = $this->getMock('T4webBase\Domain\Repository\IdentityMap');
        $this->DbRepository->setIdentityMap($identityMapMock);
        
        $identityMapMock->expects($this->once())
                ->method('offsetSet')
                ->with(111, $expectedEntityMock);
        
        $actualResult = $this->DbRepository->find($criteriaMock);
        
        $this->assertSame($expectedEntityMock, $actualResult);
    }
    
    public function testFindReturnNull() {
        $criteriaMock = $this->getMock('T4webBase\Domain\Criteria\CriteriaInterface');
        $criteriaMock->expects($this->once())
                ->method('build')
                ->with($this->identicalTo($this->queryBuilderMock));
        
        $selectMock = $this->getMockBuilder('T4webBase\Db\Select')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->queryBuilderMock->expects($this->once())
                ->method('getQuery')
                ->will($this->returnValue($selectMock));
        
        $row = array();
        
        $this->tableGatewayMock->expects($this->once())
                ->method('selectOne')
                ->with($this->equalTo($selectMock))
                ->will($this->returnValue($row));
        
        $this->dbMapperMock->expects($this->never())
                ->method('fromTableRow');
        
        $actualResult = $this->DbRepository->find($criteriaMock);
        
        $this->assertNull( $actualResult);
    }
    
    /**
     * @todo не реализовано
     * 
    public function testFindForEqualsCriteriaReturnSameEntity() {
        
        $selectMock = $this->getMockBuilder('T4webBase\Db\Select')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->queryBuilderMock->expects($this->once())
                ->method('getQuery')
                ->will($this->returnValue($selectMock));
        
        $row = array(
            'id' => 1,
            'foo' => 'bar',
        );
        
        $this->tableGatewayMock->expects($this->once())
                ->method('selectOne')
                ->with($this->equalTo($selectMock))
                ->will($this->returnValue($row));
        
        $entity = new Entity($row);
        
        $this->dbMapperMock->expects($this->once())
                ->method('fromTableRow')
                ->with($this->equalTo($row))
                ->will($this->returnValue($entity));
        
        $criteriaMock = $this->getMock('T4webBase\Domain\Criteria\CriteriaInterface');
        
        $this->assertSame(
            $this->DbRepository->find($criteriaMock),
            $this->DbRepository->find($criteriaMock)
        );
        
    }
     */
    
    public function testCount() {
        $criteriaMock = $this->getMock('T4webBase\Domain\Criteria\CriteriaInterface');
        $criteriaMock->expects($this->once())
                ->method('build')
                ->with($this->identicalTo($this->queryBuilderMock));
        
        $selectMock = $this->getMockBuilder('T4webBase\Db\Select')
                ->disableOriginalConstructor()
                ->getMock();
   
        $this->queryBuilderMock->expects($this->once())
                ->method('getQuery')
                ->will($this->returnValue($selectMock));
        
        $this->tableGatewayMock->expects($this->once())
                ->method('count')
                ->with($this->equalTo($selectMock))
                ->will($this->returnValue(5));
        
        $this->assertEquals(5, $this->DbRepository->count($criteriaMock));
    }
    
    public function testDeleteEntityWithId() {
        $id = 111;
        $entity = new \T4webBase\Domain\Entity(array('id' => $id));
        
        $this->tableGatewayMock->expects($this->once())
                ->method('deleteByPrimaryKey')
                ->with($this->equalTo($id));
        
        $this->DbRepository->delete($entity);
    }

    public function testDeleteEntityWithoutId() {
        $entity = new \T4webBase\Domain\Entity();
        
        $this->tableGatewayMock->expects($this->never())
                ->method('deleteByPrimaryKey');
        
        $this->DbRepository->delete($entity);
    }

    /**
     * @dataProvider providerFindManyByColumns
     */
    public function testFindManyByColumns($rows, $columns) {
        $expectedArray = array(array('id' => $rows[0]['id'], 'foo' => $rows[0]['foo']), array('id' => $rows[1]['id'], 'foo' => $rows[1]['foo']));

        $criteriaMock = $this->getMock('T4webBase\Domain\Criteria\CriteriaInterface');
        $criteriaMock->expects($this->once())
            ->method('build')
            ->with($this->identicalTo($this->queryBuilderMock));

        $selectMock = $this->getMockBuilder('T4webBase\Db\Select')
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryBuilderMock->expects($this->at(0))
            ->method('addColumn')
            ->will($this->returnValue('foo'));

        $this->queryBuilderMock->expects($this->at(1))
            ->method('addColumn')
            ->will($this->returnValue('id'));

        $this->queryBuilderMock->expects($this->at(2))
            ->method('getQuery')
            ->will($this->returnValue($selectMock));

        $this->tableGatewayMock->expects($this->once())
            ->method('selectMany')
            ->with($this->equalTo($selectMock))
            ->will($this->returnValue($rows));

        $actualArray = $this->DbRepository->findManyByColumns($criteriaMock, $columns);

        $this->assertEquals($expectedArray, $actualArray);
    }

    public function providerFindManyByColumns() {
        return array(
            array(
                array(
                    array('id' => 1, 'foo' => 'bar'),
                    array('id' => 2, 'foo' => 'bar')
                ),
                array('foo')
            ),
            array(
                array(
                    array('id' => 1, 'foo' => 'bar'),
                    array('id' => 2, 'foo' => 'bar')
                ),
                array('foo', 'id')
            )
        );
    }
}

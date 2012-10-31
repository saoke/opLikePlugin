<?php

/**
 * PluginNiceTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginNiceTable extends Doctrine_Table
{
  /**
   * Returns an instance of this class.
   *
   * @return object PluginNiceTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('PluginNice');
  }
  
  public function getNicedList($table, $id, $limit=0)
  {
    $q = $this->createQuery('n')->where('foreign_table = ? AND foreign_id = ?', array($table, $id));
    
    if($limit>0)
    {
      $q->limit($limit);
    }
    
    return $q->execute();
  }
  
  public function getNicedCount($table, $id)
  {
    return $this->createQuery('n')->where('foreign_table = ? AND foreign_id = ?', array($table, $id))->count();
  }
  
  public function isAlreadyNiced($memberId, $table, $id)
  {
    return $this->createQuery('n')->where('member_id = ? AND foreign_table = ? AND foreign_id = ?', array($memberId, $table, $id))->count()>0;
  }
  
  public function getMemberPager($foreignTable, $foreignId, $size, $page = 1)
  {
    if($page < 1) $page = 1;
    $q = $this->createQuery('n')->addWhere('n.foreign_table = ? AND n.foreign_id = ?', array($foreignTable, $foreignId));
    
    $pager = new sfDoctrinePager('Nice', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();
    
    return $pager;
  }
  
  public function getContentPager($memberId, $size, $page = 1)
  {
    if($page < 1) $page = 1;
    $q = $this->createQuery('n')->addWhere('n.member_id = ?', $memberId);
    
    $pager = new sfDoctrinePager('Nice', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();
    
    return $pager;
  }

  public function getNiceMemberList($table, $id, $maxId = null)
  {
    if (is_null($maxId)) $maxId = 20;
    $nices = Doctrine::getTable('Nice')->getNicedList($table, $id, $maxId);

    foreach ($nices as $nice)
    {   
      $memberIds[] = (int)$nice['member_id'];
    }   
    return Doctrine::getTable('Member')->createQuery()->whereIn('id', $memberIds)->execute();
  }
}

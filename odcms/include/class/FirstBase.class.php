<?php
if(!defined('ACCESS')) {exit('Access denied.');}
class FirstBase {
        //protected static $table_prefix = OSA_TABLE_PREFIX;
        protected static $db_container = array();
        public static function __instance($database=SAMPLE_DB_ID){
                if( @self::$db_container[$database]  == null ){
                        self::$db_container[$database] = new Medoo( $database );
                        return self::$db_container[$database];
                }
                return self::$db_container[$database];
        }


        public static function getById($key,$val) {
                if (!$val || !$key) {
                        return false;
                }
                $db=self::__instance();
                $condition[$key] = $val;

                $list = $db->select ( static::getTableName(), static::$columns, $condition );
                if ($list) {
                        return $list [0];
                }
                return array ();
        }

        public static function add($note_data) {
                if (! $note_data || ! is_array ( $note_data )) {
                        return false;
                }
                $db=self::__instance();
                $id = $db->insert ( static::getTableName(), $note_data );
                return $id;
        }
        public static function update($condition,$note_data) {
                if (! $note_data || ! is_array ( $note_data )) {
                        return false;
                }
                $db=  self::__instance();
                $id = $db->update ( static::getTableName(), $note_data,$condition );

                return $id;
        }
        public static function count($condition = '') {
                $db=self::__instance();
                $num = $db->count ( static::getTableName(), $condition );
                return $num;
        }

        public static function countByStr($condition) {
                $db=self::__instance();
                $sql="select count(1) as cc from ".static::getTableName()." where 1=1 ".$condition;

                $count = $db->query($sql)->fetchColumn();
                             return $count;
        }
        public static function del($condition = '') {
                $db=self::__instance();
                $num = $db->delete( static::getTableName(), $condition );
                return $num;
        }
   
        public static function getList($start,$page_size,$condition) {

                $db = self::__instance();
                $limit ="";
                if($page_size){
                        $limit =" limit $start,$page_size ";
                }

                $sql = "select ".static::$columns." from ".static::getTableName()." where 1=1 ".$condition ."  ".$limit;
                $list = $db->query($sql)->fetchAll();
                if ($list) {
                        return $list;
                }
                return array();
        }


        public static function page($page_size,$page_no,$condition="",$req=""){
            $page_no=$page_no<1?1:$page_no;
            $start = ($page_no - 1) * $page_size;
            $row_count = self::countByStr($condition);
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;
            $page_html=Pagination::showPager($req,$page_no,$page_size,$row_count);
            $datas= self::getList($start,$page_size,$condition);

            Template::assign ( 'page_no', $page_no );
            Template::assign ( 'page_size', $page_size);
            Template::assign ( 'row_count', $row_count );
            Template::assign ( 'page_html', $page_html );
            //Template::assign ( 'datas', $datas);
            return $datas;

        }

      public static function group($page_size,$page_no,$condition="",$req=""){
            $page_no=$page_no<1?1:$page_no;
            $start = ($page_no - 1) * $page_size;
            $row_count = static::countByStr($condition);
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;
            $page_html=Pagination::showPager($req,$page_no,$page_size,$row_count);
            $datas= static::countByDate($start,$page_size,$condition);

            Template::assign ( 'page_no', $page_no );
            Template::assign ( 'page_size', $page_size);
            Template::assign ( 'row_count', $row_count );
            Template::assign ( 'page_html', $page_html );
            //Template::assign ( 'datas', $datas);
            return $datas;

        }
}

<?php
/**************************************************
*
*   Project:      PHP
*   @Name:        class.mysql.php
*   @Author:      Andrejs Naumovs (www.naumovs.de)
*   @Version:     2.0.002
*   Description:  simply - execute a query statement  for a MySQL database!
*
*   Return:       $value  = $class->query("SELECT < COUNT(*) or MAX(*) or MIN(*) > FROM ...");
*                 $value  = $class->query("SELECT `one` FROM ... LIMIT 0,1");
*                 $object = $class->query("SELECT `one`,`two` AS second, ..., `any` FROM ... LIMIT 0,1");
*                           $one = $object->one;
*                           $two = $object->second;
*                           ...
*                           $any = $object->any
*                 $array  = $class->query("SELECT `one`,`two` AS second, ..., `any` FROM ... ");
*                           $array[0] = $object
*                                              $one = $object->one;
*                                              $two = $object->second;
*                                              ...
*                                              $any = $object->any
*                           ...
*                           ...
*                           $array[n] = $object
*                                              $one = $object->one;
*                                              $two = $object->second;
*                                              ...
*                                              $any = $object->any
*
*   Try it:       $sql = "SELECT COUNT(*) FROM `table`";
*                 $sql = "SELECT COUNT(*) as count, `any` FROM `table` WHERE 1 GROUP BY `any`";
*                 $sql = "SELECT `one` FROM `table_1` WHERE `two` IN {SELECT `two` FROM `table_2`}";
*                 ...
*                 $sql = "SELECT A from B WHERE C IN {SELECT C FROM D WHERE E IN {SELECT F FROM G WHERE H LIKE IS NULL} ORDER BY D ASC}  AND IN {SELECT I FROM J WHERE K NOT NULL}  ORDER BY A ASC";
*
***************************************************** Enjoy! ;-)   */

class mysql_db{

      var $debug; // debug messages ON or OFF

      function mysql_db(){
               // constructor is empty
      }

      function getConnect($host,$user,$password,$only_db){
               // connect
               return mysql_select_db($only_db, mysql_connect($host,$user,$password));
      }

      function runSQL($sql,$debug = FALSE){
               return $this->query($sql,$debug);
      }

      function query($sql,$debug = FALSE){
               $this->debug = $debug; // set debug
               if($debug) $this->print_in_HTML("SQL", $sql); // print sql query as debug message
               switch (1) {
                  case eregi(" IN \{SELECT", $sql):
                       switch (1) {
                          case eregi("^SELECT", $sql):
                               preg_match_all("/({[^}]*)/", $sql, $matches, PREG_SET_ORDER); // find matching sql

                               $sql_in = str_replace("{", "", strrchr($matches[0][0], "{"));
                               if(NULL == ($in = $this->set_IN($sql_in)))  return NULL;//~ ;

                               $sql = str_replace($sql_in, $in, $sql);
                               $sql = str_replace("{(", "(", $sql);
                               $sql = str_replace(")}", ")", $sql);

                               if(NULL == $this->query($sql, $debug)) return NULL;//~
                            break;
                       }
                  default:// normal MySQL syntax
                          // analyse syntax of query
                          switch (1) {
                             case eregi("^SELECT",$sql):
                                  if("" == ($result_identifier = mysql_query($sql)))return NULL;//~ no result
                                  $mysql_error = mysql_error();
                                  if($debug && "" != $mysql_error) $this->print_in_HTML("ERROR", $mysql_error);
                                  if("" != $mysql_error)return NULL;//~ error occured
                                  switch (1) {
                                     case eregi("COUNT\([^,]{1,}FROM",$sql):
                                     case eregi("MAX\(",  $sql):
                                     case eregi("MIN\(",  $sql):
                                       return mysql_result($result_identifier,0);//~ result for select COUNT, MAX, MIN
                                       break;
                                     case eregi("LIMIT 0\,1$",$sql):
                                       switch (1) {
                                          case eregi("^.+,.+FROM.+",$sql):
                                            return mysql_fetch_object($result_identifier);//~ result an object
                                            break;
                                          default:
                                            if(0 == mysql_num_rows($result_identifier))return NULL;//~
                                            return mysql_result($result_identifier,0);//~
                                       }
                                       break;
                                     default:
                                       // get result values to array of objects
                                       $i=0;
                                       while ($result = mysql_fetch_object($result_identifier))
                                              {
                                               $result_array[$i] = $result;
                                               $i++;
                                              }
                                       return @$result_array;//~
                                  }
                               break;
                             default:
                               return mysql_unbuffered_query($sql);//~
                          }
               }
      }//~ query

      function set_IN($sql) {
               if($this->debug) $this->print_in_HTML("IN SQL", $sql);
               $in  = "(";
               if (!($res = mysql_query($sql))) return NULL;
               while ($val = mysql_fetch_array($res, MYSQL_NUM))$in .= $val[0].",";
               $in = ereg_replace(",$", ")", $in);
               mysql_free_result($res);
               return $in;
      }//~ set_IN

      function print_in_HTML($titel, $string) {
               // simply: format in HTML all debug messages
               echo " <br>---- $titel<br><br> <pre>".htmlentities($string)."</pre> ";
               return TRUE;
      }//~ print_in_HTML

      function connectClose() {
               return mysql_close();
      }//~ connectClose









}//~
?>
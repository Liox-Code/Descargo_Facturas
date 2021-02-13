<?php
class mysqli {
/* Properties */
int $affected_rows;
int $connect_errno;
string $connect_error;
int $errno;
array $error_list;
string $error;
int $field_count;
string $client_info;
int $client_version;
string $host_info;
string $protocol_version;
string $server_info;
int $server_version;
string $info;
mixed $insert_id;
string $sqlstate;
int $thread_id;
int $warning_count;
/* Methods */
__construct ([ string $host = ini_get("mysqli.default_host") [, string $username = ini_get("mysqli.default_user") [, string $passwd = ini_get("mysqli.default_pw") [, string $dbname = "" [, int $port = ini_get("mysqli.default_port") [, string $socket = ini_get("mysqli.default_socket") ]]]]]] )
bool autocommit ( bool $mode )
bool change_user ( string $user , string $password , string $database )
string character_set_name ( void )
bool close ( void )
bool commit ([ int $flags [, string $name ]] )
void connect ([ string $host = ini_get("mysqli.default_host") [, string $username = ini_get("mysqli.default_user") [, string $passwd = ini_get("mysqli.default_pw") [, string $dbname = "" [, int $port = ini_get("mysqli.default_port") [, string $socket = ini_get("mysqli.default_socket") ]]]]]] )
bool debug ( string $message )
bool dump_debug_info ( void )
object get_charset ( void )
string get_client_info ( void )
bool get_connection_stats ( void )
string mysqli_stmt::get_server_info ( void )
mysqli_warning get_warnings ( void )
mysqli init ( void )
bool kill ( int $processid )
bool more_results ( void )
bool multi_query ( string $query )
bool next_result ( void )
bool options ( int $option , mixed $value )
bool ping ( void )
public static int poll ( array &$read , array &$error , array &$reject , int $sec [, int $usec ] )
mysqli_stmt prepare ( string $query )
mixed query ( string $query [, int $resultmode = MYSQLI_STORE_RESULT ] )
bool real_connect ([ string $host [, string $username [, string $passwd [, string $dbname [, int $port [, string $socket [, int $flags ]]]]]]] )
string escape_string ( string $escapestr )
string real_escape_string ( string $escapestr )
bool real_query ( string $query )
public mysqli_result reap_async_query ( void )
public bool refresh ( int $options )
bool rollback ([ int $flags [, string $name ]] )
int rpl_query_type ( string $query )
bool select_db ( string $dbname )
bool send_query ( string $query )
bool set_charset ( string $charset )
bool set_local_infile_handler ( mysqli $link , callable $read_func )
bool ssl_set ( string $key , string $cert , string $ca , string $capath , string $cipher )
string stat ( void )
mysqli_stmt stmt_init ( void )
mysqli_result store_result ([ int $option ] )
mysqli_result use_result ( void )
}
?>

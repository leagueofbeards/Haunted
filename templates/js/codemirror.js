CodeMirror.defineMode("htmlembedded",function(a,b){function g(a,b){return a.match(c,!1)?(b.token=h,e.token(a,b.scriptState)):f.token(a,b.htmlState)}function h(a,b){return a.match(d,!1)?(b.token=g,f.token(a,b.htmlState)):e.token(a,b.scriptState)}var e,f,c=b.scriptStartRegex||/^<%/i,d=b.scriptEndRegex||/^%>/i;return{startState:function(){return e=e||CodeMirror.getMode(a,b.scriptingModeSpec),f=f||CodeMirror.getMode(a,"htmlmixed"),{token:b.startOpen?h:g,htmlState:CodeMirror.startState(f),scriptState:CodeMirror.startState(e)}},token:function(a,b){return b.token(a,b)},indent:function(a,b){return a.token==g?f.indent(a.htmlState,b):e.indent?e.indent(a.scriptState,b):void 0},copyState:function(a){return{token:a.token,htmlState:CodeMirror.copyState(f,a.htmlState),scriptState:CodeMirror.copyState(e,a.scriptState)}},electricChars:"/{}:",innerMode:function(a){return a.token==h?{state:a.scriptState,mode:e}:{state:a.htmlState,mode:f}}}},"htmlmixed"),CodeMirror.defineMIME("application/x-ejs",{name:"htmlembedded",scriptingModeSpec:"javascript"}),CodeMirror.defineMIME("application/x-aspx",{name:"htmlembedded",scriptingModeSpec:"text/x-csharp"}),CodeMirror.defineMIME("application/x-jsp",{name:"htmlembedded",scriptingModeSpec:"text/x-java"}),CodeMirror.defineMIME("application/x-erb",{name:"htmlembedded",scriptingModeSpec:"ruby"}),CodeMirror.defineMode("htmlmixed",function(a,b){function i(a,b){var f=b.htmlState.tagName,g=c.token(a,b.htmlState);if("script"==f&&/\btag\b/.test(g)&&">"==a.current()){var h=a.string.slice(Math.max(0,a.pos-100),a.pos).match(/\btype\s*=\s*("[^"]+"|'[^']+'|\S+)[^<]*$/i);h=h?h[1]:"",h&&/[\"\']/.test(h.charAt(0))&&(h=h.slice(1,h.length-1));for(var i=0;i<e.length;++i){var j=e[i];if("string"==typeof j.matches?h==j.matches:j.matches.test(h)){j.mode&&(b.token=k,b.localMode=j.mode,b.localState=j.mode.startState&&j.mode.startState(c.indent(b.htmlState,"")));break}}}else"style"==f&&/\btag\b/.test(g)&&">"==a.current()&&(b.token=l,b.localMode=d,b.localState=d.startState(c.indent(b.htmlState,"")));return g}function j(a,b,c){var f,d=a.current(),e=d.search(b);return e>-1?a.backUp(d.length-e):(f=d.match(/<\/?$/))&&(a.backUp(d.length),a.match(b,!1)||a.match(d[0])),c}function k(a,b){return a.match(/^<\/\s*script\s*>/i,!1)?(b.token=i,b.localState=b.localMode=null,i(a,b)):j(a,/<\/\s*script\s*>/,b.localMode.token(a,b.localState))}function l(a,b){return a.match(/^<\/\s*style\s*>/i,!1)?(b.token=i,b.localState=b.localMode=null,i(a,b)):j(a,/<\/\s*style\s*>/,d.token(a,b.localState))}var c=CodeMirror.getMode(a,{name:"xml",htmlMode:!0}),d=CodeMirror.getMode(a,"css"),e=[],f=b&&b.scriptTypes;if(e.push({matches:/^(?:text|application)\/(?:x-)?(?:java|ecma)script$|^$/i,mode:CodeMirror.getMode(a,"javascript")}),f)for(var g=0;g<f.length;++g){var h=f[g];e.push({matches:h.matches,mode:h.mode&&CodeMirror.getMode(a,h.mode)})}return e.push({matches:/./,mode:CodeMirror.getMode(a,"text/plain")}),{startState:function(){var a=c.startState();return{token:i,localMode:null,localState:null,htmlState:a}},copyState:function(a){if(a.localState)var b=CodeMirror.copyState(a.localMode,a.localState);return{token:a.token,localMode:a.localMode,localState:b,htmlState:CodeMirror.copyState(c,a.htmlState)}},token:function(a,b){return b.token(a,b)},indent:function(a,b){return!a.localMode||/^\s*<\//.test(b)?c.indent(a.htmlState,b):a.localMode.indent?a.localMode.indent(a.localState,b):CodeMirror.Pass},electricChars:"/{}:",innerMode:function(a){return{state:a.localState||a.htmlState,mode:a.localMode||c}}}},"xml","javascript","css"),CodeMirror.defineMIME("text/html","htmlmixed"),CodeMirror.defineMode("markdown",function(a,b){function C(a,b,c){return b.f=b.inline=c,c(a,b)}function D(a,b,c){return b.f=b.block=c,c(a,b)}function E(a){return a.linkTitle=!1,a.em=!1,a.strong=!1,a.quote=0,c||a.f!=G||(a.f=K,a.block=F),a.trailingSpace=0,a.trailingSpaceNewLine=!1,a.thisLineHasContent=!1,null}function F(a,c){var d=c.list!==!1;if(c.list!==!1&&c.indentationDiff>=0?(c.indentationDiff<4&&(c.indentation-=c.indentationDiff),c.list=null):c.list!==!1&&c.indentation>0?(c.list=null,c.listDepth=Math.floor(c.indentation/4)):c.list!==!1&&(c.list=!1,c.listDepth=0),c.indentationDiff>=4)return c.indentation-=4,a.skipToEnd(),i;if(a.eatSpace())return null;if("#"===a.peek()||c.prevLineHasContent&&a.match(A))c.header=!0;else if(a.eat(">"))for(c.indentation++,c.quote=1,a.eatSpace();a.eat(">");)a.eatSpace(),c.quote++;else{if("["===a.peek())return C(a,c,M);if(a.match(w,!0))return o;if(c.prevLineHasContent&&!d||!a.match(x,!0)&&!a.match(y,!0)){if(b.fencedCodeBlocks&&a.match(/^```([\w+#]*)/,!0))return c.localMode=f(RegExp.$1),c.localMode&&(c.localState=c.localMode.startState()),D(a,c,H),i}else c.indentation+=4,c.list=!0,c.listDepth++,b.taskLists&&a.match(z,!1)&&(c.taskList=!0)}return C(a,c,c.inline)}function G(a,b){var e=d.token(a,b.htmlState);return c&&"tag"===e&&"openTag"!==b.htmlState.type&&!b.htmlState.context&&(b.f=K,b.block=F),b.md_inside&&-1!=a.current().indexOf(">")&&(b.f=K,b.block=F,b.htmlState.context=void 0),e}function H(a,b){return a.sol()&&a.match(/^```/,!0)?(b.localMode=b.localState=null,b.f=K,b.block=F,i):b.localMode?b.localMode.token(a,b.localState):(a.skipToEnd(),i)}function I(a){var b=[];if(a.taskOpen)return"meta";if(a.taskClosed)return"property";if(a.strong&&b.push(v),a.em&&b.push(u),a.linkText&&b.push(s),a.code&&b.push(i),a.header&&b.push(h),a.quote&&b.push(a.quote%2?j:k),a.list!==!1){var c=(a.listDepth-1)%3;c?1===c?b.push(m):b.push(n):b.push(l)}return a.trailingSpaceNewLine?b.push("trailing-space-new-line"):a.trailingSpace&&b.push("trailing-space-"+(a.trailingSpace%2?"a":"b")),b.length?b.join(" "):null}function J(a,b){return a.match(B,!0)?I(b):void 0}function K(a,c){var d=c.text(a,c);if("undefined"!=typeof d)return d;if(c.list)return c.list=null,I(c);if(c.taskList){var e="x"!==a.match(z,!0)[1];return e?c.taskOpen=!0:c.taskClosed=!0,c.taskList=!1,I(c)}c.taskOpen=!1,c.taskClosed=!1;var f=a.next();if("\\"===f)return a.next(),I(c);if(c.linkTitle){c.linkTitle=!1;var h=f;"("===f&&(h=")"),h=(h+"").replace(/([.?*+^$[\]\\(){}|-])/g,"\\$1");var i="^\\s*(?:[^"+h+"\\\\]+|\\\\\\\\|\\\\.)"+h;if(a.match(new RegExp(i),!0))return t}if("`"===f){var j=I(c),k=a.pos;a.eatWhile("`");var l=1+a.pos-k;return c.code?l===g?(c.code=!1,j):I(c):(g=l,c.code=!0,I(c))}if(c.code)return I(c);if("!"===f&&a.match(/\[[^\]]*\] ?(?:\(|\[)/,!1))return a.match(/\[[^\]]*\]/),c.inline=c.f=L,p;if("["===f&&a.match(/.*\](\(| ?\[)/,!1))return c.linkText=!0,I(c);if("]"===f&&c.linkText){var m=I(c);return c.linkText=!1,c.inline=c.f=L,m}if("<"===f&&a.match(/^(https?|ftps?):\/\/(?:[^\\>]|\\.)+>/,!1))return C(a,c,Q(q,">"));if("<"===f&&a.match(/^[^> \\]+@(?:[^\\>]|\\.)+>/,!1))return C(a,c,Q(r,">"));if("<"===f&&a.match(/^\w/,!1)){if(-1!=a.string.indexOf(">")){var n=a.string.substring(1,a.string.indexOf(">"));/markdown\s*=\s*('|"){0,1}1('|"){0,1}/.test(n)&&(c.md_inside=!0)}return a.backUp(1),D(a,c,G)}if("<"===f&&a.match(/^\/\w*?>/))return c.md_inside=!1,"tag";var o=!1;if(!b.underscoresBreakWords&&"_"===f&&"_"!==a.peek()&&a.match(/(\w)/,!1)){var s=a.pos-2;if(s>=0){var u=a.string.charAt(s);"_"!==u&&u.match(/(\w)/,!1)&&(o=!0)}}var j=I(c);if("*"===f||"_"===f&&!o){if(c.strong===f&&a.eat(f))return c.strong=!1,j;if(!c.strong&&a.eat(f))return c.strong=f,I(c);if(c.em===f)return c.em=!1,j;if(!c.em)return c.em=f,I(c)}else if(" "===f&&(a.eat("*")||a.eat("_"))){if(" "===a.peek())return I(c);a.backUp(1)}return" "===f&&(a.match(/ +$/,!1)?c.trailingSpace++:c.trailingSpace&&(c.trailingSpaceNewLine=!0)),I(c)}function L(a,b){if(a.eatSpace())return null;var c=a.next();return"("===c||"["===c?C(a,b,Q(t,"("===c?")":"]")):"error"}function M(a,b){return a.match(/^[^\]]*\]:/,!0)?(b.f=N,s):C(a,b,K)}function N(a,b){return a.eatSpace()?null:(a.match(/^[^\s]+/,!0),void 0===a.peek()?b.linkTitle=!0:a.match(/^(?:\s+(?:"(?:[^"\\]|\\\\|\\.)+"|'(?:[^'\\]|\\\\|\\.)+'|\((?:[^)\\]|\\\\|\\.)+\)))?/,!0),b.f=b.inline=K,t)}function P(a){return O[a]||(a=(a+"").replace(/([.?*+^$[\]\\(){}|-])/g,"\\$1"),O[a]=new RegExp("^(?:[^\\\\]|\\\\.)*?("+a+")")),O[a]}function Q(a,b,c){return c=c||K,function(d,e){return d.match(P(b)),e.inline=e.f=c,a}}var c=CodeMirror.modes.hasOwnProperty("xml"),d=CodeMirror.getMode(a,c?{name:"xml",htmlMode:!0}:"text/plain"),e={html:"htmlmixed",js:"javascript",json:"application/json",c:"text/x-csrc","c++":"text/x-c++src",java:"text/x-java",csharp:"text/x-csharp","c#":"text/x-csharp",scala:"text/x-scala"},f=function(){var b,f,c={},d={},g=[];for(var h in CodeMirror.modes)CodeMirror.modes.propertyIsEnumerable(h)&&g.push(h);for(b=0;b<g.length;b++)c[g[b]]=g[b];var i=[];for(var h in CodeMirror.mimeModes)CodeMirror.mimeModes.propertyIsEnumerable(h)&&i.push({mime:h,mode:CodeMirror.mimeModes[h]});for(b=0;b<i.length;b++)f=i[b].mime,d[f]=i[b].mime;for(var j in e)(e[j]in c||e[j]in d)&&(c[j]=e[j]);return function(b){return c[b]?CodeMirror.getMode(a,c[b]):null}}();void 0===b.underscoresBreakWords&&(b.underscoresBreakWords=!0),void 0===b.fencedCodeBlocks&&(b.fencedCodeBlocks=!1),void 0===b.taskLists&&(b.taskLists=!1);var g=0,h="header",i="comment",j="atom",k="number",l="variable-2",m="variable-3",n="keyword",o="hr",p="tag",q="link",r="link",s="link",t="string",u="em",v="strong",w=/^([*\-=_])(?:\s*\1){2,}\s*$/,x=/^[*\-+]\s+/,y=/^[0-9]+\.\s+/,z=/^\[(x| )\](?=\s)/,A=/^(?:\={1,}|-{1,})$/,B=/^[^!\[\]*_\\<>` "'(]+/,O=[];return{startState:function(){return{f:F,prevLineHasContent:!1,thisLineHasContent:!1,block:F,htmlState:CodeMirror.startState(d),indentation:0,inline:K,text:J,linkText:!1,linkTitle:!1,em:!1,strong:!1,header:!1,taskList:!1,list:!1,listDepth:0,quote:0,trailingSpace:0,trailingSpaceNewLine:!1}},copyState:function(a){return{f:a.f,prevLineHasContent:a.prevLineHasContent,thisLineHasContent:a.thisLineHasContent,block:a.block,htmlState:CodeMirror.copyState(d,a.htmlState),indentation:a.indentation,localMode:a.localMode,localState:a.localMode?CodeMirror.copyState(a.localMode,a.localState):null,inline:a.inline,text:a.text,linkTitle:a.linkTitle,em:a.em,strong:a.strong,header:a.header,taskList:a.taskList,list:a.list,listDepth:a.listDepth,quote:a.quote,trailingSpace:a.trailingSpace,trailingSpaceNewLine:a.trailingSpaceNewLine,md_inside:a.md_inside}},token:function(a,b){if(a.sol()){if(a.match(/^\s*$/,!0))return b.prevLineHasContent=!1,E(b);b.prevLineHasContent=b.thisLineHasContent,b.thisLineHasContent=!0,b.header=!1,b.taskList=!1,b.code=!1,b.trailingSpace=0,b.trailingSpaceNewLine=!1,b.f=b.block;var c=a.match(/^\s*/,!0)[0].replace(/\t/g,"    ").length,d=4*Math.floor((c-b.indentation)/4);d>4&&(d=4);var e=b.indentation+d;if(b.indentationDiff=e-b.indentation,b.indentation=e,c>0)return null}return b.f(a,b)},blankLine:E,getType:I}},"xml"),CodeMirror.defineMIME("text/x-markdown","markdown"),function(){function a(a){for(var b={},c=a.split(" "),d=0;d<c.length;++d)b[c[d]]=!0;return b}function b(a){return function(b,c){return b.match(a)?c.tokenize=null:b.skipToEnd(),"string"}}var c={name:"clike",keywords:a("abstract and array as break case catch class clone const continue declare default do else elseif enddeclare endfor endforeach endif endswitch endwhile extends final for foreach function global goto if implements interface instanceof namespace new or private protected public static switch throw trait try use var while xor die echo empty exit eval include include_once isset list require require_once return print unset __halt_compiler self static parent"),blockKeywords:a("catch do else elseif for foreach if switch try while"),atoms:a("true false null TRUE FALSE NULL __CLASS__ __DIR__ __FILE__ __LINE__ __METHOD__ __FUNCTION__ __NAMESPACE__"),builtin:a("func_num_args func_get_arg func_get_args strlen strcmp strncmp strcasecmp strncasecmp each error_reporting define defined trigger_error user_error set_error_handler restore_error_handler get_declared_classes get_loaded_extensions extension_loaded get_extension_funcs debug_backtrace constant bin2hex sleep usleep time mktime gmmktime strftime gmstrftime strtotime date gmdate getdate localtime checkdate flush wordwrap htmlspecialchars htmlentities html_entity_decode md5 md5_file crc32 getimagesize image_type_to_mime_type phpinfo phpversion phpcredits strnatcmp strnatcasecmp substr_count strspn strcspn strtok strtoupper strtolower strpos strrpos strrev hebrev hebrevc nl2br basename dirname pathinfo stripslashes stripcslashes strstr stristr strrchr str_shuffle str_word_count strcoll substr substr_replace quotemeta ucfirst ucwords strtr addslashes addcslashes rtrim str_replace str_repeat count_chars chunk_split trim ltrim strip_tags similar_text explode implode setlocale localeconv parse_str str_pad chop strchr sprintf printf vprintf vsprintf sscanf fscanf parse_url urlencode urldecode rawurlencode rawurldecode readlink linkinfo link unlink exec system escapeshellcmd escapeshellarg passthru shell_exec proc_open proc_close rand srand getrandmax mt_rand mt_srand mt_getrandmax base64_decode base64_encode abs ceil floor round is_finite is_nan is_infinite bindec hexdec octdec decbin decoct dechex base_convert number_format fmod ip2long long2ip getenv putenv getopt microtime gettimeofday getrusage uniqid quoted_printable_decode set_time_limit get_cfg_var magic_quotes_runtime set_magic_quotes_runtime get_magic_quotes_gpc get_magic_quotes_runtime import_request_variables error_log serialize unserialize memory_get_usage var_dump var_export debug_zval_dump print_r highlight_file show_source highlight_string ini_get ini_get_all ini_set ini_alter ini_restore get_include_path set_include_path restore_include_path setcookie header headers_sent connection_aborted connection_status ignore_user_abort parse_ini_file is_uploaded_file move_uploaded_file intval floatval doubleval strval gettype settype is_null is_resource is_bool is_long is_float is_int is_integer is_double is_real is_numeric is_string is_array is_object is_scalar ereg ereg_replace eregi eregi_replace split spliti join sql_regcase dl pclose popen readfile rewind rmdir umask fclose feof fgetc fgets fgetss fread fopen fpassthru ftruncate fstat fseek ftell fflush fwrite fputs mkdir rename copy tempnam tmpfile file file_get_contents stream_select stream_context_create stream_context_set_params stream_context_set_option stream_context_get_options stream_filter_prepend stream_filter_append fgetcsv flock get_meta_tags stream_set_write_buffer set_file_buffer set_socket_blocking stream_set_blocking socket_set_blocking stream_get_meta_data stream_register_wrapper stream_wrapper_register stream_set_timeout socket_set_timeout socket_get_status realpath fnmatch fsockopen pfsockopen pack unpack get_browser crypt opendir closedir chdir getcwd rewinddir readdir dir glob fileatime filectime filegroup fileinode filemtime fileowner fileperms filesize filetype file_exists is_writable is_writeable is_readable is_executable is_file is_dir is_link stat lstat chown touch clearstatcache mail ob_start ob_flush ob_clean ob_end_flush ob_end_clean ob_get_flush ob_get_clean ob_get_length ob_get_level ob_get_status ob_get_contents ob_implicit_flush ob_list_handlers ksort krsort natsort natcasesort asort arsort sort rsort usort uasort uksort shuffle array_walk count end prev next reset current key min max in_array array_search extract compact array_fill range array_multisort array_push array_pop array_shift array_unshift array_splice array_slice array_merge array_merge_recursive array_keys array_values array_count_values array_reverse array_reduce array_pad array_flip array_change_key_case array_rand array_unique array_intersect array_intersect_assoc array_diff array_diff_assoc array_sum array_filter array_map array_chunk array_key_exists pos sizeof key_exists assert assert_options version_compare ftok str_rot13 aggregate session_name session_module_name session_save_path session_id session_regenerate_id session_decode session_register session_unregister session_is_registered session_encode session_start session_destroy session_unset session_set_save_handler session_cache_limiter session_cache_expire session_set_cookie_params session_get_cookie_params session_write_close preg_match preg_match_all preg_replace preg_replace_callback preg_split preg_quote preg_grep overload ctype_alnum ctype_alpha ctype_cntrl ctype_digit ctype_lower ctype_graph ctype_print ctype_punct ctype_space ctype_upper ctype_xdigit virtual apache_request_headers apache_note apache_lookup_uri apache_child_terminate apache_setenv apache_response_headers apache_get_version getallheaders mysql_connect mysql_pconnect mysql_close mysql_select_db mysql_create_db mysql_drop_db mysql_query mysql_unbuffered_query mysql_db_query mysql_list_dbs mysql_list_tables mysql_list_fields mysql_list_processes mysql_error mysql_errno mysql_affected_rows mysql_insert_id mysql_result mysql_num_rows mysql_num_fields mysql_fetch_row mysql_fetch_array mysql_fetch_assoc mysql_fetch_object mysql_data_seek mysql_fetch_lengths mysql_fetch_field mysql_field_seek mysql_free_result mysql_field_name mysql_field_table mysql_field_len mysql_field_type mysql_field_flags mysql_escape_string mysql_real_escape_string mysql_stat mysql_thread_id mysql_client_encoding mysql_get_client_info mysql_get_host_info mysql_get_proto_info mysql_get_server_info mysql_info mysql mysql_fieldname mysql_fieldtable mysql_fieldlen mysql_fieldtype mysql_fieldflags mysql_selectdb mysql_createdb mysql_dropdb mysql_freeresult mysql_numfields mysql_numrows mysql_listdbs mysql_listtables mysql_listfields mysql_db_name mysql_dbname mysql_tablename mysql_table_name pg_connect pg_pconnect pg_close pg_connection_status pg_connection_busy pg_connection_reset pg_host pg_dbname pg_port pg_tty pg_options pg_ping pg_query pg_send_query pg_cancel_query pg_fetch_result pg_fetch_row pg_fetch_assoc pg_fetch_array pg_fetch_object pg_fetch_all pg_affected_rows pg_get_result pg_result_seek pg_result_status pg_free_result pg_last_oid pg_num_rows pg_num_fields pg_field_name pg_field_num pg_field_size pg_field_type pg_field_prtlen pg_field_is_null pg_get_notify pg_get_pid pg_result_error pg_last_error pg_last_notice pg_put_line pg_end_copy pg_copy_to pg_copy_from pg_trace pg_untrace pg_lo_create pg_lo_unlink pg_lo_open pg_lo_close pg_lo_read pg_lo_write pg_lo_read_all pg_lo_import pg_lo_export pg_lo_seek pg_lo_tell pg_escape_string pg_escape_bytea pg_unescape_bytea pg_client_encoding pg_set_client_encoding pg_meta_data pg_convert pg_insert pg_update pg_delete pg_select pg_exec pg_getlastoid pg_cmdtuples pg_errormessage pg_numrows pg_numfields pg_fieldname pg_fieldsize pg_fieldtype pg_fieldnum pg_fieldprtlen pg_fieldisnull pg_freeresult pg_result pg_loreadall pg_locreate pg_lounlink pg_loopen pg_loclose pg_loread pg_lowrite pg_loimport pg_loexport echo print global static exit array empty eval isset unset die include require include_once require_once"),multiLineStrings:!0,hooks:{$:function(a){return a.eatWhile(/[\w\$_]/),"variable-2"},"<":function(a,c){return a.match(/<</)?(a.eatWhile(/[\w\.]/),c.tokenize=b(a.current().slice(3)),c.tokenize(a,c)):!1},"#":function(a){for(;!a.eol()&&!a.match("?>",!1);)a.next();return"comment"},"/":function(a){if(a.eat("/")){for(;!a.eol()&&!a.match("?>",!1);)a.next();return"comment"}return!1}}};CodeMirror.defineMode("php",function(a,b){function f(a,b){var c=b.curMode==e;if(a.sol()&&'"'!=b.pending&&(b.pending=null),c)return c&&null==b.php.tokenize&&a.match("?>")?(b.curMode=d,b.curState=b.html,"meta"):e.token(a,b.curState);if(a.match(/^<\?\w*/))return b.curMode=e,b.curState=b.php,"meta";if('"'==b.pending){for(;!a.eol()&&'"'!=a.next(););var f="string"}else if(b.pending&&a.pos<b.pending.end){a.pos=b.pending.end;var f=b.pending.style}else var f=d.token(a,b.curState);b.pending=null;var g=a.current(),h=g.search(/<\?/);return-1!=h&&(b.pending="string"==f&&/\"$/.test(g)&&!/\?>/.test(g)?'"':{end:a.pos,style:f},a.backUp(g.length-h)),f}var d=CodeMirror.getMode(a,"text/html"),e=CodeMirror.getMode(a,c);return{startState:function(){var a=CodeMirror.startState(d),c=CodeMirror.startState(e);return{html:a,php:c,curMode:b.startOpen?e:d,curState:b.startOpen?c:a,pending:null}},copyState:function(a){var h,b=a.html,c=CodeMirror.copyState(d,b),f=a.php,g=CodeMirror.copyState(e,f);return h=a.curMode==d?c:g,{html:c,php:g,curMode:a.curMode,curState:h,pending:a.pending}},token:f,indent:function(a,b){return a.curMode!=e&&/^\s*<\//.test(b)||a.curMode==e&&/^\?>/.test(b)?d.indent(a.html,b):a.curMode.indent(a.curState,b)},electricChars:"/{}:",blockCommentStart:"/*",blockCommentEnd:"*/",lineComment:"//",innerMode:function(a){return{state:a.curState,mode:a.curMode}}}},"htmlmixed","clike"),CodeMirror.defineMIME("application/x-httpd-php","php"),CodeMirror.defineMIME("application/x-httpd-php-open",{name:"php",startOpen:!0}),CodeMirror.defineMIME("text/x-php",c)}();
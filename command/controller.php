<?php

function _generate_controller_file($table, $entity_structs, $entity_relationships)
{/*{{{*/
    $resource_plural = $table.'s';
    $resource_id_key = $table.'_id';

    $list_str = [];
    $input_str = [];
    foreach ($entity_structs as $struct) {
        $list_str[] = "\$inputs['$struct']";
        $input_str[] = "'$struct'";
    }

    $input_content = "\$inputs = [];\n    list(".implode(", ", $list_str).") = input_list(".implode(", ", $input_str).");\n    \$inputs = array_filter(\$inputs);";

    $content = "<?php

if_get('/%s', function ()
{/*{{{*/
    %s

    return [
        'succ' => true,
        'data' => dao('%s')->find_all_by_column(\$inputs),
    ];
});/*}}}*/

if_put('/%s', function ()
{/*{{{*/
    %s

    $%s = %s::create();

    foreach (\$inputs as \$property => \$value) {
        $%s->{\$property} = \$value;
    }

    return [
        'succ' => true,
        'data' => $%s,
    ];
});/*}}}*/

if_get('/%s/*', function ($%s)
{/*{{{*/
    $%s = dao('%s')->find($%s);
    otherwise($%s->is_not_null(), '%s not found');

    return [
        'succ' => true,
        'data' => $%s,
    ];
});/*}}}*/

if_post('/%s/*', function ($%s)
{/*{{{*/
    $%s = dao('%s')->find($%s);
    otherwise($%s->is_not_null(), '%s not found');

    %s

    foreach (\$inputs as \$property => \$value) {
        $%s->{\$property} = \$value;
    }

    return [
        'succ' => true,
        'data' => $%s,
    ];
});/*}}}*/

if_delete('/%s/*', function ($%s)
{/*{{{*/
    $%s = dao('%s')->find($%s);
    otherwise($%s->is_not_null(), '%s not found');

    $%s->delete();

    return [
        'succ' => true,
        'data' => $%s,
    ];
});/*}}}*/";

    return sprintf($content, 
        $resource_plural,
        $input_content,
        $table,

        $resource_plural,
        $input_content,
        $table, $table,
        $table,
        $table,

        $resource_plural, $resource_id_key,
        $table, $table, $resource_id_key,
        $table, $table,
        $table,

        $resource_plural, $resource_id_key,
        $table, $table, $resource_id_key,
        $table, $table,
        $input_content,
        $table,
        $table,

        $resource_plural, $resource_id_key,
        $table, $table, $resource_id_key,
        $table, $table,
        $table,
        $table
    );
}/*}}}*/

command('controller:make-restful-from-db', '初始化 controller', function ()
{/*{{{*/
    $table = command_paramater('table_name');

    $schema_infos = db_query("show create table `$table`");
    $schema_info = reset($schema_infos);

    $entity_structs = $entity_relationships = [];

    $lines = explode("\n", $schema_info['Create Table']);

    foreach ($lines as $i => $line) {

        $line = trim($line);

        if (stristr($line, 'CONSTRAINT')) {
            unset($lines[$i]);
            continue;
        }

        if (stristr($line, 'CREATE TABLE')) continue;
        if (stristr($line, 'PRIMARY KEY')) continue;
        if (stristr($line, ') ENGINE=')) continue;
        if (stristr($line, '`id`')) continue;
        if (stristr($line, '`version`')) continue;
        if (stristr($line, '`create_time`')) continue;
        if (stristr($line, '`update_time`')) continue;
        if (stristr($line, '`delete_time`')) continue;


        preg_match('/^`(.*)`/', $line, $matches);
        if ($matches) {
            $entity_structs[] = $matches[1];
            continue;
        }

        preg_match('/^KEY `fk_'.$table.'_(.*)_idx` \(`(.*)`\)/', $line, $matches);
        if ($matches) {
            $relate_to = preg_replace('/[0-9]/', '', $matches[1]);
            $relation_name = str_replace('_id', '', $matches[2]);
            $entity_relationships[] = [
                'type' => 'belongs_to',
                'relate_to' => $relate_to,
                'relation_name' => $relation_name,
            ];
        }
    }

    error_log(_generate_controller_file($table, $entity_structs, $entity_relationships), 3, $file = CONTROLLER_DIR.'/'.$table.'.php');
    echo $file."\n";
});/*}}}*/

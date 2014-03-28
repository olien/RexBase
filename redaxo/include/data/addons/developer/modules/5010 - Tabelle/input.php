<script type="text/javascript">

jQuery(function($){ 
    
//--- Functions ---///

    function get_dyn_table_data() {
        var table_data = '';
        i=0;
        $('div#dyntable table tr[class!="control"]').each(function() {
            if(i>0) {
                table_data = table_data+'[ROW|ROW]';
            }
            i++;
            
            j=0;
            $(this).children('.dyn_content').each(function() {
                if(j>0) {
                table_data = table_data+'[COL|COL]';
                }
                j++;     
                var fieldvalue = $(this).children().first().val();
                var fieldvalue_clean = fieldvalue.replace('[COL|COL]', '[col|col]').replace('[ROW|ROW]', '[row|row]');
                table_data = table_data+fieldvalue_clean;
            });
        });
        $('#debugout').html(table_data);
        $('#dyntable_string').val(table_data);

    }     
    
    function generate_dyn_table() {
        var table_data = $('#dyntable_string').val();
        var table_rows = table_data.split('[ROW|ROW]');
        var table_cols = table_rows[0].split('[COL|COL]');
        for(var i=1; i<table_cols.length;i++) {
            add_dyn_table_col();
        }
        for(var i=1; (i+1)<table_rows.length;i++) {
           add_dyn_table_row();            
        }       
        update_dyn_table_counter();
 
        for(var i=0;i<table_rows.length;i++) {
            table_cols = table_rows[i].split('[COL|COL]');
            for(var j=0;j<table_cols.length;j++) {
                if(i==0) {
                    $('th[data-col="'+(j+2)+'"][data-row="'+(i+2)+'"] input').val(table_cols[j]);
                }
                else {
                    $('td[data-col="'+(j+2)+'"][data-row="'+(i+2)+'"] textarea').html(table_cols[j]);
                }
            }   
        }
        get_dyn_table_data();
    }

    function update_dyn_table_counter() {
        i=0;
        $('div#dyntable table tr').each(function() {
            i++;
            j=0;
            $(this).children().each(function() {
                j++;
                $(this).attr('data-row', i);
                $(this).attr('data-col', j);
            });
        });  
    }

    function add_dyn_table_col() {
        $('div#dyntable_show table tr[class!="control"][class!="dyn_head"]').append('<td class="dyn_content"><textarea style="width:95%"></textarea></td>');
        $('div#dyntable_show table tr[class="dyn_head"][class!="control"]').append('<th class="dyn_content"><input style="width:95%"></input></th>');
        $('div#dyntable_show table tr[class="control"]').append('<th><a href="#" class="dyntable_del_col" >[löschen]</a> <br> <a href="#" class="left">[links]</a> <a href="#" class="right">[rechts]</a></th>');
    } 

    function add_dyn_table_row() {
        var count = $('div#dyntable_show table tr.control th').size();
        var rowout = '';
        for (var i=0; i<(count-1); i++){
            rowout = rowout+'<td class="dyn_content"><textarea style="width:95%"></textarea></td>';
        }

        $('div#dyntable_show table').append('<tr><td><a href="#" class="dyntable_del_row">[löschen]</a><br><a href="#" class="up" >[hoch]</a><br><a href="#" class="down">[runter]</a></td>'+rowout+'</tr>');
    }  

    function move_table_col(from,to) {
        var rows = $('tr', '#dyntable_show table');
        var cols;
        rows.each(function() {
            cols = $(this).children('th, td');
            cols.eq(from).detach().insertBefore(cols.eq(to));
        });
        get_dyn_table_data();
        update_dyn_table_counter();
    }

//--- Events ---//

    $('#dyntable_add_col').click(function(e) {
        e.preventDefault();
        add_dyn_table_col();
        update_dyn_table_counter();
        get_dyn_table_data();
    });
    $('#dyntable_add_row').click(function(e) {
        e.preventDefault();
        add_dyn_table_row();
        update_dyn_table_counter();
        get_dyn_table_data();
    });
  
      
    $('#dyntable_show').on('click', '.up,.down', function(e){
        e.preventDefault();
        var row = $(this).closest('tr');
        
        if ($(this).is(".up")) {
            var index = $( "tr" ).index( $(this).parent().parent() );
            if(index>2)
                row.insertBefore(row.prev());
        } else {
            row.insertAfter(row.next());
        }
        get_dyn_table_data();
    });


    $('#dyntable_show').on('click', '.left,.right', function(e) {
        e.preventDefault();
        var index = $( "th" ).index( $(this).parent() );
        if($(this).is('.left')) {
            if(index > 1)
                move_table_col(index, index-1);
        }
        else {
            move_table_col(index+1, index);
        }    
    });

    $('#dyntable_show').on('click', '.dyntable_del_col' ,function(e) {
        e.preventDefault();
        var col = $(this).parent().attr('data-col');
        $('th[data-col="'+col+'"],td[data-col="'+col+'"]').remove();
        get_dyn_table_data();
     });

     $('#dyntable_show').on('click', '.dyntable_del_row', function(e) {
         e.preventDefault();
         var row = $(this).parent().attr('data-row');
         $(this).parent().parent().remove();
         get_dyn_table_data();
     });

     $('div#dyntable').on('change', 'input,textarea', function() {
            get_dyn_table_data();
     });

//--- Initial execution ---//

     generate_dyn_table();

});
</script>


<div id="dyntable">    
    <input id="dyntable_string" type="hidden" class="text'.$classes.$wc.'" name="VALUE[1]" id="test" value="REX_VALUE[1]">
    <div id="dyntable_show">    
        <table class="rex-table" style="padding-right:10px;" >
                <tr class="control">
                    <th><a id="dyntable_add_row" style="display:block;float:left;width:20px; height:20px; background-image:url(media/pfeil_down.gif); background-position:center center; background-repeat:no-repeat;text-indent:-9999px;" href="#">[Zeile hinzu]</a><a id="dyntable_add_col" style="display:block;width:20px; height:20px; background-image:url(media/pfeil_right.gif); background-position:center center; background-repat:no-repeat; float:left;text-indent:-9999px;" href="#">[Spalte hinzu]</a></th><th><a href="#" class="dyntable_del_col" >[löschen]</a> <br> <a href="#" class="left">[links]</a> <a href="#" class="right">[rechts]</a></th>
                </tr>          
                <tr class="dyn_head">
                        <th></th>
                        <th class="dyn_content"><input style="width:95%"></input></th>
                </tr>
                <tr>
                        <td><a href="#" class="dyntable_del_row">[löschen]</a><br><a href="#" class="up" >[hoch]</a><br><a href="#" class="down">[runter]</a></td>
                        <td class="dyn_content"><textarea style="width:95%"></textarea></td>
                </tr>             
        </table>
    </div>
</div>
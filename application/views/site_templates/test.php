<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/15/14
 * Time: 7:45 PM
 */
?>

<script>

    $(document).ready(function() {
        $('#field-organizationId').change(function() {
            var selectedValue = $('#field-organizationId').val();
            alert('post:'+'ajax_extension/companyId/organizationId/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')));
            $.post('ajax_extension/companyId/organizationId/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')), {}, function(data) {
                alert('BACK');
                alert('data'+data);
                var $el = $('#field-companyId');
                var newOptions = data;
                $el.empty(); // remove old options
                $.each(newOptions, function(key, value) {
                    $el.append($('<option></option>')
                        .attr('value', key).text(value));
                });
                $el.chosen().trigger('liszt:updated');
            },'json');
        });
    });

</script>
<script>

    $(document).ready(function() {
        $('#field-companyId').change(function() {
            var selectedValue = $('#field-companyId').val();
            //alert('post:'+'ajax_extension/departmentId/companyId/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')));
            $.post(
                'ajax_extension/departmentId/companyId/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')),
                {},
                function(data) {
                //alert('BACK');
                //alert('data'+data);
                var $el = $('#field-departmentId');
                var newOptions = data;
                $el.empty(); // remove old options
                $.each(newOptions, function(key, value) {
                    $el.append($('<option></option>')
                        .attr('value', key).text(value));
                });
                $el.chosen().trigger('liszt:updated');
            },'json');
        });
    });

</script>
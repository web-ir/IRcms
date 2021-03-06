$(function () {
    /** BEGIN DATATABLE EXAMPLE **/
    if ($('#datatable-place').length > 0) {
        selectedIds = [];
        var table = $('#datatable-place').DataTable({
            "dom": '<"top"fl>t<"bottom"ip>r',
            "lengthMenu": [[10, 25, 50, 100], ["10 pozycji", "25 pozycji", "50 pozycji", "100 pozycji"]],
            "language": {
                "url": "/datatables/pl_PL.json"
            },
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ajaxSource": "/cms-ir/place",
            "serverMethod": "POST",
            "paginate": true,
            "sortable": true,
            "searchable": true,
            "order": [[4, "asc"]],
            "rowReorder": {
                update: false,
                selector: 'td:not(:last-child):not(:first-child)'
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "render": function (data, type, row) {   // o, v contains the object and value for the column
                        if (type === 'display') {
                            return '<div class="checkbox"><label><input type="checkbox" class="check-row i-grey" /></label></div>';
                        }
                        return data;
                    },
                    "className": "dt-body-center",
                    "sortable": false
                },
                {
                    "targets": [5],
                    "render": function (data, type, row) {   // o, v contains the object and value for the column
                        if (type === 'display') {
                            return '<a href="place/preview/' + data + '" class="btn btn-info" data-toggle="tooltip" title="Podgląd"><i class="fa fa-eye"></i></a> ' +
                                '<a href="place/edit/' + data + '" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' +
                                '<a href="place/delete/' + data + '" id="' + data + '" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
                        }
                        return data;
                    },
                    "className": "dt-body-action",
                    "sortable": false
                },
                {
                    "sortable": false,
                    "targets": [-1]
                },
                {
                    "targets": [ 4 ],
                    "visible": false,
                    "className": "never"
                }
            ],
            "drawCallback": function (data) {
                selectedIds = [];

                $('input.i-grey').iCheck({
                    checkboxClass: 'icheckbox_minimal-grey',
                    radioClass: 'iradio_minimal-grey',
                    increaseArea: '20%'
                });

                setTimeout(function () {
                    // select row
                    $('.check-row').on('ifChecked', function (event) {
                        var tr = $(this).parent().parent().parent().parent().parent();
                        var data = table.row(tr).data();
                        var id = data[0];
                        selectedIds.push(id);
                        tr.addClass('selected-row');
                        //console.log(selectedIds);
                    });

                    // deselect row
                    $('.check-row').on('ifUnchecked', function (event) {
                        var tr = $(this).parent().parent().parent().parent().parent();
                        var data = table.row(tr).data();
                        var id = data[0];
                        var index = selectedIds.indexOf(id);
                        selectedIds.splice(index, 1);
                        tr.removeClass('selected-row');
                        //console.log(selectedIds);
                    });

                    //check all
                    $('.check-all').on('ifChecked', function (event) {
                        selectedIds = [];
                        $('#datatable-place tbody tr td .check-row').iCheck('check');
                        //console.log(selectedIds);
                    });

                    //uncheck all
                    $('.check-all').on('ifUnchecked', function (event) {
                        selectedIds = [];
                        $('#datatable-place tbody tr td .check-row').iCheck('uncheck');
                        //console.log(selectedIds);
                    });
                }, 0);

                //massive action select
                if ($('#datatable-place_length select[name="massive-action"]').length === 0) {
                    $('#datatable-place_length').append($('.massive-action').html());
                }

                //add new button
                if ($('.the-box .bottom .btn-facebook').length === 0) {
                    $('.the-box .bottom').append($('.btn-facebook').parent().html());
                    $('.row .col-sm-12 .btn-facebook').remove();
                }

                //massive actions
                $('select[name="massive-action"]').off('change').on('change', function () {
                    var action = $(this).val();
                    if (action.length > 0) {
                        var modal = action + 'MassiveModal';
                        $('#' + modal).on('show.bs.modal', function () {
                            if (selectedIds.length > 0) {
                                $('#' + modal + ' .content').show();
                                $('#' + modal + ' .modal-footer input[type="submit"][value="Tak"]').show();
                                $('#' + modal + ' .message').hide();

                                if (action === 'delete') // massive delete
                                {
                                    $('#' + modal + ' form input').off('click').on('click', function (ev) {
                                        ev.preventDefault();
                                        var del = $(this).val();
                                        del == 'Tak' ? $('.spinner').show() : $('.spinner').hide();

                                        $.ajax({
                                            type: "POST",
                                            url: "/cms-ir/place/delete/1",
                                            dataType: 'json',
                                            data: {
                                                modal: true,
                                                id: selectedIds,
                                                del: del
                                            },
                                            success: function (json) {
                                                $('.spinner').hide();
                                                $('.check-all').iCheck('uncheck');
                                                //$('#' + modal).modal('hide');
                                                $('select[name="massive-action"]').val('');
                                                table.ajax.reload();
                                            }
                                        });

                                    });
                                }
                            } else {
                                $('#' + modal + ' .content').hide();
                                $('#' + modal + ' .modal-footer input[type="submit"][value="Tak"]').hide();
                                $('#' + modal + ' .modal-footer input[type="submit"][value="Zapisz"]').hide();
                                $('#' + modal + ' .message').show();
                            }
                        }).modal('show');
                    }
                });

                $('#deleteMassiveModal, #statusMassiveModal').off().on('hidden.bs.modal', function () {
                    $('select[name="massive-action"]').val('');
                }).modal('hide');
            }
        });

        // delete modal
        $('#datatable-place tbody').on('click', '.btn-danger', function (e) {
            e.preventDefault();
            var entityId = $(this).attr('id');
            $('#deleteModal').off().on('show.bs.modal', function () {

                $('#deleteModal form input').off('click').on('click', function (ev) {
                    ev.preventDefault();
                    var del = $(this).val();
                    del == 'Tak' ? $('.spinner').show() : $('.spinner').hide();

                    $.ajax({
                        type: "POST",
                        url: "/cms-ir/place/delete/" +entityId,
                        dataType : 'json',
                        data: {
                            modal: true,
                            id: entityId,
                            del: del
                        },
                        success: function(json)
                        {
                            $('.spinner').hide();
                            $('#deleteModal').modal('hide');
                            table.ajax.reload();
                        }
                    });

                });

            }).modal('show');
        });

        table.on( 'row-reorder', function ( e, diff, edit ) {
            var result = [];

            for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                var rowData = table.row( diff[i].node ).data();
                result[rowData[0]] = diff[i].newPosition + 1;
            }

            $.ajax({
                type: "POST",
                url: "/cms-ir/place/change-position",
                dataType : 'json',
                data: {
                    position: result
                },
                success: function(json)
                {
                    table.ajax.reload();
                }
            });
        });
    }
});
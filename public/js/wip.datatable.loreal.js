var table = $('#wipTable');

 var oTable = table.dataTable({
 "processing": true,
 "serverSide": true,
 "ajax": {
 url: '/configuration/wip/datatable',
 method: 'POST'
 },
 columns: [
 { data: 'id', name: 'id' },
 { data: 'store.store_name_1', name: 'store.store_name_1', class: 'namewrapper' },
 { data: 'store.city.city_name', name: 'store.city.city_name', orderable: false, searchable: false, class: 'namewrapper'},
 { data: 'brand.name', name: 'brand.name', class: 'namewrapper', "defaultContent": "" },
 { data: 'store.account.name', name: 'store.account.name', class: 'namewrapper', "defaultContent": "" , orderable: false},
 { data: 'store.channel', name: 'store.channel', class: 'namewrapper', orderable: false },
 { data: 'status', name: 'status', class: 'namewrapper' },
 { data: 'filling_date', name: 'filling_date', class: 'namewrapper' },
 { data: 'effective_date', name: 'effective_date', class: 'namewrapper' },
 { data: 'replacement.interview_date', name: 'replacement.interview_date', orderable: false, searchable: false, class: 'namewrapper', "defaultContent": "" },
 { data: 'replacement.status', name: 'replacement.status',orderable: false, searchable: false, class: 'namewrapper', "defaultContent": "" },
 { data: 'candidate', name: 'replacement.ba_replace.name', orderable: false, searchable: false, class: 'namewrapper', "defaultContent": "" },
 { data: 'replacement.description', name: 'fullfield', orderable: false, searchable: false, class: 'namewrapper', "defaultContent": "" },
 { data: 'hc', name: 'hc', class: 'namewrapper', "defaultContent": "" , orderable: false, searchable: false}
 ]

 });
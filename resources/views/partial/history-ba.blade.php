<div class="modal fade" id="history-ba"  role="basic" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Data History BA</h4>
            </div>
            <div class="modal-body">
                <div class="mt-element-list">
                    <div class="mt-list-head list-todo blue-steel">
                        <div class="list-head-title-container">
                            <h4 class="list-title">Data History BA @{{ ba.nama }}</h4>
                        </div>
                    </div>
                    <div class="mt-list-container list-todo">
                        <div class="list-todo-line red"></div>
                        <ul>
                            <li class="mt-list-item" v-for="(history, key) in baHistory">
                                <div class="list-todo-icon bg-white font-blue-steel">
                                    <i class="fa fa-database"></i>
                                </div>
                                <div class="list-todo-item " :class="decideColor(history)">
                                    <a class="list-toggle-container font-white collapsed" data-toggle="collapse" :href="idHistory(history)"  aria-expanded="false">
                                        <div class="list-toggle done uppercase">
                                            <div class="list-toggle-title bold"> @{{ history }} </div>
                                            <div class="badge badge-default pull-right bold">@{{ length(history) }}</div>
                                        </div>
                                    </a>
                                    <div class="task-list panel-collapse collapse" :id="history" aria-expanded="false" style="height: 0px;">
                                        <ul>
                                            <li class="task-list-item" v-for="(item, key) in baHistory[history]" >
                                                <div class="task-icon">
                                                    <a href="javascript:;">
                                                        <i class="fa fa-database"></i>
                                                    </a>
                                                </div>
                                                <div class="task-content">
                                                    <h4 class="uppercase bold">
                                                        <a href="javascript:"> @{{ historyInfo(item) }} </a>
                                                    </h4>
                                                    <ul>
                                                        <li v-for="detail in baHistory[history][item]">
                                                            <h5 class="uppercase"> @{{ detail.store.store_name_1 }} </h5>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
export function UseDataTable(fetchListApi, _tableConfig = {}) {
  const tableConfig = {
    api: fetchListApi,
    filter: {..._tableConfig.filter},
    pagination: {
      page: 1,
      total: 0,
      perPage: 20
      , ..._tableConfig.pagination
    },
    showReload: _tableConfig.showReload!==null?_tableConfig.showReload:false,
    showPagination: _tableConfig.showPagination!==null?_tableConfig.showPagination:true,
    columns:  _tableConfig.columns?_tableConfig.columns:[],
    config: {
      columns: [],
      sticky: true,
      actionColumn: true,
      scroll: "{ x: 1500, y: 300 }",
      bordered: true,
      showHeader: true,
      ..._tableConfig.config
    },
    listActions: _tableConfig.listActions?_tableConfig.listActions:[],
    itemActions: _tableConfig.itemActions?_tableConfig.itemActions:[],
    ..._tableConfig
  }
  return tableConfig
}

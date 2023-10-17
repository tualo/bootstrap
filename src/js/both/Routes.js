Ext.define('Tualo.routes.Bootstrap', {
    statics: {
        load: async function() {
            return [];
        }
    }, 
    url: 'bootstrap',
    handler: {
        action: function (tablename) {
            let type = 'dsview';
            let mainView = Ext.getApplication().getMainView();
            let tablename = 'getbootstrap_scss';
            let tablenamecase = tablename.toLocaleUpperCase().substring(0, 1) + tablename.toLowerCase().slice(1);
            let stage = mainView.getComponent('dashboard_dashboard').getComponent('stage');
            let component = null;
            stage.items.each(function(item){
                if (item.xtype==type+'_'+tablename.toLowerCase()){
                    component = item;
                }
            });

            if(!Ext.isEmpty( component )){
                stage.setActiveItem(component);
            }else{
                Ext.getApplication().addView('Tualo.DataSets.' + type + '.' + tablenamecase);
            }
        },
        before: function (action) {
            
            action.resume();
        }
    }
});



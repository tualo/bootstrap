Ext.define('Tualo.bootstrap.commands.AutoCompiler', {
  statics: {
    glyph: 'gear-complex-code',
    title: 'Auto-Kompilieren',
    tooltip: 'Auto-Kompilieren'
  },
  extend: 'Ext.panel.Panel',
  alias: 'widget.bootstrap_autocompiler_command',
  layout: 'fit',
  items: [
    {
      xtype: 'form',
      itemId: 'syncform',
      bodyPadding: '25px',
      items: [
        {
          xtype: 'label',
          text: 'Durch klicken auf *Starten* wird der Programmcode neu erstellt, wenn Sie speichern klicken.',
        }


      ]
    }, {
      hidden: true,
      xtype: 'panel',
      itemId: 'waitpanel',
      layout: {
        type: 'vbox',
        align: 'center'
      },
      items: [
        {
          xtype: 'component',
          cls: 'lds-container',
          html: '<div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
            + '<div><h3>CSS wird erstellt</h3>'
            + '<span>Einen Moment bitte ...</span></div>'
        }
      ]
    }
  ],
  loadRecord: function (record, records, selectedrecords) {
    this.record = record;
    console.log('loadRecord', record);
    this.records = records;
    this.selectedrecords = selectedrecords;

  },
  getNextText: function () {
    return 'Starten';
  },

  run: async function () {
    let me = this;
    me.record.store.on('datachanged', async function () {
      let res = await (await fetch('./bootstrap/compile')).json();
      if (res.success !== true) {
        if (res.return) {
          Ext.toast({
            html: res.return.join('<br>'),
            title: 'Fehler',
            align: 't',
            iconCls: 'fa fa-warning'
          });
        } else {
          Ext.toast({
            html: res.msg,
            title: 'Fehler',
            align: 't',
            iconCls: 'fa fa-warning'
          });
        }
      }
    });

    me.getComponent('syncform').hide();
    me.getComponent('waitpanel').show();

    return true;
  }
});

/**
 * @file
 * Attaches the behaviors for the Multistep UI table.
 */

(function ($, Drupal, once, drupalSettings) {

  /**
   * Attaches the multistepUIOverview behavior.
   */
  Drupal.behaviors.multistepUIDisplayOverview = {
    attach(context, settings) {
      once(
        'multistep-display-overview',
        'table#multistep-display-overview',
        context,
      ).forEach((overview) => {
        Drupal.multistepUIOverview.attach(
          overview,
          settings.multistepStatusData,
          Drupal.multistepUIDisplayOverview,
        );
      });
    },
  };

  /**
   * Namespace for the multistep UI overview.
   */
  Drupal.multistepUIOverview = {

    /**
     * Attaches the multistepUIOverview behavior.
     */
    attach(table, rowsData, rowHandlers) {
      const tableDrag = Drupal.tableDrag[table.id];
      tableDrag.onDrop = this.onDrop;
      tableDrag.row.prototype.onSwap = this.onSwap;

      // Create row handlers.
      $(table)
        .find('tr.draggable')
        .each(function () {
          // Extract server-side data for the row.
          const row = this;
          if (row.id in rowsData) {
            const data = rowsData[row.id];
            data.tableDrag = tableDrag;

            // Create the row handler, make it accessible from the DOM row
            // element.
            const rowHandler = new rowHandlers[data.rowHandler](row, data);
            $(row).data('multistepUIRowHandler', rowHandler);
          }
        });
    },

    /**
     * Lets row handlers react when a row is dropped into a new status.
     */
    onDrop() {
      const dragObject = this;
      const row = dragObject.rowObject.element;
      const $row = $(row);
      const rowHandler = $row.data('multistepUIRowHandler');
      if (typeof rowHandler !== 'undefined') {
        const statusRow = $row.prevAll('tr.status-message').get(0);
        const status = statusRow.className.replace(
          /([^ ]+[ ]+)*status-([^ ]+)-message([ ]+[^ ]+)*/,
          '$2',
        );
        rowHandler.statusChange(status);
      }
    },

    /**
     * Refreshes placeholder rows in empty statuses while a row is being dragged.
     */
    onSwap(draggedRow) {
      const rowObject = this;
      $(rowObject.table)
        .find('tr.status-message')
        .each(function () {
          const $this = $(this);

          if (
            $this.prev('tr').get(0) ===
            rowObject.group[rowObject.group.length - 1]
          ) {
            rowObject.swap('after', this);
          }
          // This status has become empty.
          if (
            $this.next('tr').length === 0 ||
            !$this.next('tr')[0].matches('.draggable')
          ) {
            $this.removeClass('status-populated').addClass('status-empty');
          }
          // This region has become populated.
          else if (this.matches('.status-empty')) {
            $this.removeClass('status-empty').addClass('status-populated');
          }
        });
    },

  };

  /**
   * Row handlers for the 'Manage display' screen.
   */
  Drupal.multistepUIDisplayOverview = {};

  /**
   * Constructor for a 'multistep' row handler.
   */
  Drupal.multistepUIDisplayOverview.field = function (row, data) {
    this.row = row;
    this.name = data.name;
    this.status = data.status;
    this.tableDrag = data.tableDrag;
    this.defaultPlugin = data.defaultPlugin;
    this.$statusSelect = $(row).find('select.field-status');

    return this;
  };

  Drupal.multistepUIDisplayOverview.field.prototype = {

    /**
     * Reacts to a row being changed statuses.
     */
    statusChange(status) {
      if (this.$statusSelect.length) {
        this.$statusSelect.val(status);
      }
      const refreshRows = {};
      refreshRows[this.name] = this.$statusSelect.get(0);
      return refreshRows;
    },
  };

})(jQuery, Drupal, once, drupalSettings);

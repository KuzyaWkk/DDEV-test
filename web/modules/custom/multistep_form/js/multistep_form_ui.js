/**
 * @file
 * Attaches the behaviors for the Multistep UI table.
 */

(function ($, Drupal, once, drupalSettings, debounce) {

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

      // Add custom tabledrag callbacks.
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

    onChange() {
      const $trigger = $(this);
      const $row = $trigger.closest('tr');
      if ($trigger.closest('.ajax-new-content').length !== 0) {
        return;
      }

      const rowHandler = $row.data('multistepUIRowHandler');  //
      const refreshRows = {};
      refreshRows[rowHandler.name] = $trigger.get(0);

      // Handle status change.
      const status = rowHandler.getStatus();

      if (status !== rowHandler.status) {
        // Let the row handler deal with the status change.
        $.extend(refreshRows, rowHandler.statusChange(status));
        // Update the row status.
        rowHandler.status = status;
      }

      if ($trigger.closest('.tabledrag-hide').length) {
        const thisTableDrag = Drupal.tableDrag['multistep-display-overview'];
        // eslint-disable-next-line new-cap
        const rowObject = new thisTableDrag.row(
          $row[0],
          '',
          thisTableDrag.indentEnabled,
          thisTableDrag.maxDepth,
          true,
        );
        rowObject.markChanged();
        rowObject.addChangedWarning();
      } else {
        // Ajax-update the rows.
        Drupal.multistepUIOverview.AJAXRefreshRows(refreshRows);
      }
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

    /**
     * Triggers Ajax refresh of selected rows.
     */
    AJAXRefreshRows(rows) {
      // Separate keys and values.
      const rowNames = [];
      const ajaxElements = [];
      Object.keys(rows || {}).forEach((rowName) => {
        rowNames.push(rowName);
        ajaxElements.push(rows[rowName]);
      });
      if (rowNames.length) {
        // Add a throbber next each of the ajaxElements.
        $(ajaxElements).after(Drupal.theme.ajaxProgressThrobber());
        const $refreshRows = $('input[name=refresh_rows]');
        if ($refreshRows.length) {
          // Fire the Ajax update.
          $refreshRows[0].value = rowNames.join(' ');
        }
        once(
          'edit-refresh',
          'input[data-drupal-selector="edit-refresh"]',
        ).forEach((input) => {
          // Keep track of the element that was focused prior to triggering the
          // mousedown event on the hidden submit button.
          let returnFocus = {
            drupalSelector: null,
            scrollY: null,
          };
          // Use jQuery on to listen as the mousedown event is propagated by
          // jQuery trigger().
          $(input).on('mousedown', () => {
            returnFocus = {
              drupalSelector: document.activeElement.hasAttribute(
                'data-drupal-selector',
              )
                ? document.activeElement.getAttribute('data-drupal-selector')
                : false,
              scrollY: window.scrollY,
            };
          });
          input.addEventListener('focus', () => {
            if (returnFocus.drupalSelector) {
              // Refocus the element that lost focus due to this hidden submit
              // button being triggered by a mousedown event.
              document
                .querySelector(
                  `[data-drupal-selector="${returnFocus.drupalSelector}"]`,
                )
                .focus();
            }
            // Ensure the scroll position is the same as when the input was
            // initially changed.
            window.scrollTo({
              top: returnFocus.scrollY,
            });
            returnFocus = {};
          });
        });
        $('input[data-drupal-selector="edit-refresh"]').trigger('mousedown');

        // Disabled elements do not appear in POST ajax data, so we mark the
        // elements disabled only after firing the request.
        $(ajaxElements).prop('disabled', true);
      }
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

    $(row).find('select').on('change', Drupal.multistepUIOverview.onChange);

    return this;
  };

  Drupal.multistepUIDisplayOverview.field.prototype = {
    /**
     * Returns the status corresponding to the current form values of the row.
     */
    getStatus() {
      if (this.$statusSelect.length) {
        return this.$statusSelect[0].value;
      }
    },

    /**
     * Reacts to a row being changed statuses.
     */
    statusChange(status) {
      if (this.$statusSelect.length) {
        // Set the status of the select list.
        this.$statusSelect[0].value = status;
      }
      const refreshRows = {};
      refreshRows[this.name] = this.$statusSelect.get(0);
      return refreshRows;
    },
  };

})(jQuery, Drupal, once, drupalSettings, Drupal.debounce);

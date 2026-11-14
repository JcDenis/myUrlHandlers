/*global dotclear */
'use strict';

dotclear.ready(() => {
  const msg = dotclear.getData('myurlhandlers');
  document.querySelector('input[name="act_restore"]')?.addEventListener('click', (event) =>
    dotclear.confirm(msg.confirm_reset, event),
  );
});

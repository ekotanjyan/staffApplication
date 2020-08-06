/* Our suctom JS wrappwer function to show the global toast
 * options:
 * -------------
 * type (string) toast type {info|danger|success|warning}
 * title (string) toast title
 * body (string) toast body message
 */
window.toastJS = (options) => {
  const defaultOptions = {
    type: 'info',
    title: false,
    body: false
  };
  const opts = Object.assign(defaultOptions, options);
  const toastElem = $('.toast');
  const toastHeaderClasses = `toast-header text-white bg-${opts.type}`;
  const toastBodyClasses = `toast-body text-${opts.type}`;

  if( !opts.body ){
    return;
  }

  //set toast title text
  if( opts.title ){
    toastElem.find('.toast-title').html(opts.title);
  }

  //set toast header html classes
  toastElem.find('.toast-header').removeClass().addClass(toastHeaderClasses);
  //set toast body text and html classes
  toastElem.find('.toast-body').html(opts.body).removeClass().addClass(toastBodyClasses);

  //show it
  toastElem.toast('show');
}

import moment from 'moment';

export function formatDate(date, format = 'YYYY-MM-DD') {
  return moment(date).format(format);
}
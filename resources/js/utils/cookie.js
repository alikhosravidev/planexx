export const getCookie = (name) => {
  const cookie = document.cookie || '';
  const cookieArray = cookie.split(';');
  for (let i = 0; i < cookieArray.length; i++) {
    const part = cookieArray[i].trim();
    if (!part) continue;
    const [rawName, ...rest] = part.split('=');
    if (rawName === name) {
      const value = rest.join('=');
      try {
        return decodeURIComponent(value);
      } catch (_) {
        return value;
      }
    }
  }
  return '';
};

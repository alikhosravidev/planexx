<!-- فارسی‌سازی اعداد -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // تبدیل اعداد انگلیسی به فارسی
  const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
  
  function toPersianNumber(num) {
    return String(num).replace(/\d/g, (x) => persianNumbers[parseInt(x)]);
  }
  
  // تبدیل تمام textContent ها به جز input, textarea, select
  function convertNumbers(element) {
    if (!element) return;
    
    // نادیده گرفتن input ها
    if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA' || element.tagName === 'SELECT') {
      return;
    }
    
    // فقط text node ها را تبدیل کن
    for (let node of element.childNodes) {
      if (node.nodeType === Node.TEXT_NODE) {
        node.textContent = toPersianNumber(node.textContent);
      } else if (node.nodeType === Node.ELEMENT_NODE) {
        convertNumbers(node);
      }
    }
  }
  
  // اجرا روی کل body
  convertNumbers(document.body);
});
</script>


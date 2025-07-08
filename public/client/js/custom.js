var wordMap = {
  "add to cart": "Thêm vào giỏ hàng",
  "all": "Tất cả",
  "days": "Ngày",
  "hours": "Giờ",
  "mins": "Phút",
  "secs": "Giây",
  "read now": "Đọc ngay",
  "add to wishlist": "Thêm vào danh sách yêu thích",
  "ask about products": "Hỏi về sản phẩm",
  "categories": "Danh mục",
  "comments": "Bình luận",
  "trending items": "Sản phẩm nổi bật",
  "today's flash sales": "Khuyến mãi chớp nhoáng hôm nay",
  "next post": "Bài tiếp theo",
  "prev post": "Bài viết trước",
  "share": "Chia sẻ",
  "products": "Sản phẩm",
  "compare": "So sánh",
};

function replaceText(node) {
  if (node.nodeType === Node.TEXT_NODE) {
    var text = node.nodeValue;
    text = text.replace(regex, function (match) {
      return wordMap[match.toLowerCase()] || match;
    });
    node.nodeValue = text;
  } else if (node.nodeType === Node.ELEMENT_NODE) {
    for (var i = 0; i < node.childNodes.length; i++) {
      replaceText(node.childNodes[i]);
    }
  }
}

var regex = new RegExp(Object.keys(wordMap).join("|"), "ig");
replaceText(document.body);

//  Custom mini cart
document.addEventListener("DOMContentLoaded", function() {
  var cartDrawer = document.querySelector(".cart-drawer");
  var observer = new MutationObserver(function(mutationsList) {
    for (var mutation of mutationsList) {
      if (mutation.target.classList.contains("added")) {
        cartDrawer.classList.add("active");
      }
    }
  });
  var addToCartButton = document.querySelector(".single_add_to_cart_button");

  observer.observe(addToCartButton, { attributes: true, attributeFilter: ["class"] });
});

# RESTful web API design


Dưới đây là một số nguyên tắc thiết kế chính của RESTful API:

- Các API REST được thiết kế xung quanh các tài nguyên, là bất kì loại đối tượng, dữ liệu hoặc dịch vụ nào mà client có thể truy cập.
- Một tài nguyên có một định danh, là một URI xác định duy nhất tài nguyên đó. Ví dụ: URI cho một đơn đặt hàng của khách hàng cụ thể có thể là:
```http request
GET https://adventure-works.com/orders/1
```
- Client tương tác với một dịch vụ bằng cách trao đổi các đại diện của tài nguyên. Nhiều API web sự dung JSON làm định dạng tra đổi. Ví dụ một yêu cầu GET tới URI được liệt kê ở trên có thể trả về nội dung phản hồi này:
```json
{"orderId":1,"orderValue":99.90,"productId":1,"quantity":1}
```
- Các API REST sử dụng một giao diện thống nhất, giúp phân tách việc triển khai máy khách và dịch vụ. Đối với các API REST được xây dựng trên HTTP, giao diện thống nhất bao gồm việc sử dụng các động từ HTTP tiêu chuẩn để thực hiện các hoạt động trên tài nguyên. Các thao tác phổ biến nhất là **GET**, **POST**, **PUT**, **PATCH** và **DELETE**.
- Các API REST sử dụng mô hình yêu cầu không trạng thái. Các yêu cầu HTTP phải độc lập và có thể xảy ra theo bất kỳ thứ tự nào, vì vậy việc lưu giữ thông tin trạng thái tạm thời giữa các yêu cầu là không khả thi. Nơi duy nhất mà thông tin được lưu trữ là trong chính các tài nguyên và mỗi yêu cầu phải là một hoạt động nguyên tử. Ràng buộc này cho phép các dịch vụ web có khả năng mở rộng cao, vì không cần phải giữ lại bất kỳ mối quan hệ nào giữa các máy khách và máy chủ cụ thể. Bất kỳ máy chủ nào cũng có thể xử lý bất kỳ yêu cầu nào từ bất kỳ máy khách nào. Điều đó nói rằng, các yếu tố khác có thể hạn chế khả năng mở rộng. Ví dụ: nhiều dịch vụ web ghi vào kho dữ liệu phụ trợ, có thể khó mở rộng quy mô. Để biết thêm thông tin về các chiến lược mở rộng quy mô kho dữ liệu, hãy xem Phân vùng dữ liệu theo chiều ngang, chiều dọc và chức năng.
- REST APIs are driven by hypermedia links that are contained in the representation. For example, the following shows a JSON representation of an order. It contains links to get or update the customer associated with the order.
```json
{
    "orderID":3,
    "productID":2,
    "quantity":4,
    "orderValue":16.60,
    "links": [
        {"rel":"product","href":"https://adventure-works.com/customers/3", "action":"GET" },
        {"rel":"product","href":"https://adventure-works.com/customers/3", "action":"PUT" }
    ]
}
```

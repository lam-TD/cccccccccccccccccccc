# RESTful web API design

Dưới đây là một số nguyên tắc thiết kế chính của RESTful API:

- Các API REST được thiết kế xung quanh các tài nguyên, là bất kì loại đối tượng, dữ liệu hoặc dịch vụ nào mà client có thể truy cập.
- Một tài nguyên là một URI xác định duy nhất tài nguyên đó. Ví dụ: URI cho một đơn đặt hàng của khách hàng cụ thể có thể là:

```text
https://adventure-works.com/orders/1
```

- Client tương tác với một dịch vụ bằng cách trao đổi các đại diện của tài nguyên. Nhiều API web sử dụng JSON làm định dạng trao đổi. Ví dụ một yêu cầu GET tới URI được liệt kê dưới đây có thể trả về nội dung phản hồi như sau:

```json
{"orderId":1,"orderValue":99.90,"productId":1,"quantity":1}
```

- Các API REST sử dụng một giao diện thống nhất. Đối với các API REST được xây dựng trên HTTP, giao diện thống nhất bao gồm việc sử dụng các HTTP action tiêu chuẩn để thực hiện các hoạt động trên tài nguyên. Các action phổ biến nhất là `GET`, `POST`, `PUT`, `PATCH` và `DELETE`.

- Các API REST sử dụng mô hình **stateless request**.

>
> **Stateless** còn gọi là **tình trạng phi trạng thái**. Cụ thể, stateless là **thiết kế không lưu dữ liệu của client trên server**. Điều đó có nghĩa là sau khi client gửi dữ liệu lên server, khi server thực thi > xong, trả kết quả thì quan hệ giữa client và server sẽ bị cắt đứt. Server sẽ không lưu bất kỳ dữ liệu gì của client.
>

- Các yêu cầu HTTP phải độc lập và có thể xảy ra theo bất kỳ thứ tự nào, vì vậy việc lưu giữ thông tin trạng thái tạm thời giữa các request là không khả thi. Nơi duy nhất mà thông tin được lưu trữ là trong chính các tài nguyên và mỗi request phải là một hoạt động nguyên tử(atomic operation - Một thao tác nguyên tử là một thao tác không thể được chia thành các thao tác nhỏ hơn.). Ràng buộc này cho phép các dịch vụ web có khả năng mở rộng cao, vì server sẽ không lưu bất kỳ dữ liệu gì của client.

- Các API REST được điều khiển bởi các `hypermedia links` có trong thông tin được trả về. Ví dụ đươi đây cho thấy dữ liệu được trả về dạng JSON của một đơn đặt hàng. Nó chứa các liên kết để lấy hoặc cập nhật khách hàng được liên kết với đơn đặt hàng.

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

## Organize the API design around resources

Ví dụ: trong hệ thống thương mại điện tử, các thực thể chính có thể là khách hàng và đơn đặt hàng. Việc tạo đơn hàng có thể đạt được bằng cách gửi một yêu cầu HTTP POST có chứa thông tin đơn hàng. Phản hồi HTTP cho biết đơn hàng đã được đặt thành công hay chưa. Khi có thể, các URI tài nguyên phải dựa trên danh từ (resource) chứ không phải động từ (các hoạt động trên resource).

```text
https://adventure-works.com/orders // Good

https://adventure-works.com/create-order // Avoid
```

Một resource không nhất thiết phải dựa trên một dữ liệu duy nhất. Ví dụ: một thông tin đơn hàng có thể được lưu dưới dạng một số bảng trong cơ sở dữ liệu, nhưng được hiển thị cho khách hàng dưới dạng một thực thể duy nhất. Tránh tạo các API chỉ phản ánh cấu trúc bên trong của cơ sở dữ liệu. Mục đích của REST là mô hình hóa các thực thể và các hoạt động mà một ứng dụng có thể thực hiện trên các thực thể đó. Khách hàng không nên tiếp xúc với việc triển khai nội bộ.

Các thực thể thường được nhóm lại với nhau thành các tập hợp (đơn đặt hàng, khách hàng). Một collection(bộ sưu tập) là một tài nguyên riêng biệt và phải có URI của riêng nó. Ví dụ: URI sau có thể đại diện cho tập hợp các đơn đặt hàng:

```http
https://adventure-works.com/orders
```

Khi gửi 1 yêu cầu GET tới URI truy xuất danh sách các mục trong bộ sưu tập. Mỗi mục trong bộ sưu tập cũng có URI duy nhất của riêng nó. Yêu cầu GET tới URI của mặt hàng trả về thông tin chi tiết của mặt hàng đó.

Áp dụng quy ước đặt tên nhất quán trong URI. Nói chung, việc sử dụng danh từ số nhiều cho các URI tham chiếu các tập hợp sẽ rất hữu ích. Đó là một phương pháp hay để tổ chức các URI cho các bộ sưu tập và các mục thành một hệ thống phân cấp. Ví dụ: `/customers` là đường dẫn đến collection khách hàng và `/customers/5` là đường dẫn đến khách hàng có ID bằng 5. Cách tiếp cận này giúp giữ cho API web trực quan. Ngoài ra, nhiều web API frameworks có thể định tuyến các yêu cầu dựa trên các đường dẫn URI được tham số hóa, vì vậy bạn có thể xác định một tuyến đường cho đường dẫn `/customers/{id}`.

Cũng xem xét các mối quan hệ giữa các loại tài nguyên khác nhau và cách bạn có thể hiển thị các liên kết này. Ví dụ: `/customers/5/orders` có thể đại diện cho tất cả các đơn đặt hàng cho khách hàng 5. Bạn cũng có thể đi theo hướng khác và đại diện cho sự liên kết từ một đơn đặt hàng trở lại khách hàng với một URI chẳng hạn như `/orders/99/customers`. Tuy nhiên, việc mở rộng mô hình này quá xa có thể trở nên khó thực hiện. Giải pháp tốt hơn là cung cấp các liên kết có thể điều hướng đến các tài nguyên được liên kết trong nội dung của thông báo phản hồi HTTP. Cơ chế này được mô tả chi tiết hơn trong phần [sử dụng HATEOAS để cho phép điều hướng đến các tài nguyên liên quan](https://martinfowler.com/articles/richardsonMaturityModel.html).

Trong các hệ thống phức tạp hơn, có thể dẫn để cung cấp các URI cho phép khách hàng điều hướng qua một số cấp độ của mối quan hệ, chẳng hạn như `/customers/1/orders/99/products`. Tuy nhiên, mức độ phức tạp này có thể khó duy trì và không linh hoạt nếu mối quan hệ giữa các nguồn lực thay đổi trong tương lai. Thay vào đó, hãy cố gắng giữ cho các URI tương đối đơn giản. Khi một ứng dụng có tham chiếu đến một tài nguyên, có thể sử dụng tham chiếu này để tìm các mục liên quan đến tài nguyên đó. Truy vấn trước có thể được thay thế bằng URI `/customers/1/orders` để tìm tất cả đơn hàng cho khách hàng 1 và sau đó `/orders/99/products` để tìm sản phẩm trong đơn hàng này.

> **_NOTE:_** Tránh yêu cầu các URI tài nguyên phức tạp hơn _collection/item/collection_.

Tránh giới thiệu sự phụ thuộc giữa API web và các nguồn dữ liệu cơ bản. Ví dụ: nếu dữ liệu của bạn được lưu trữ trong cơ sở dữ liệu quan hệ, thì web API không cần hiển thị mỗi table dưới dạng tập hợp tài nguyên. Trên thực tế, đó có lẽ là một thiết kế kém. Thay vào đó, hãy nghĩ về API web như một phần trừu tượng của cơ sở dữ liệu. Nếu cần, hãy giới thiệu một lớp ánh xạ giữa cơ sở dữ liệu và API web. Bằng cách đó, các ứng dụng khách được cách ly khỏi những thay đổi đối với lược đồ cơ sở dữ liệu bên dưới.

Cuối cùng, có thể không ánh xạ mọi hoạt động được thực hiện bởi API web tới một tài nguyên cụ thể. Bạn có thể xử lý các tình huống phi tài nguyên như vậy thông qua các yêu cầu HTTP gọi một hàm và trả về kết quả dưới dạng thông báo phản hồi HTTP. Ví dụ: một API web triển khai các hoạt động máy tính đơn giản như cộng và trừ có thể cung cấp các URI hiển thị các hoạt động này dưới dạng tài nguyên giả và sử dụng chuỗi truy vấn để chỉ định các tham số được yêu cầu. Ví dụ: một yêu cầu GET tới URI _/add?operand1=99&operand2=1_ sẽ trả về một thông báo phản hồi với phần nội dung chứa giá trị 100. Tuy nhiên, chỉ sử dụng các dạng URI này một cách tiết kiệm.

## Xác định các hoạt động API theo các phương thức HTTP

Giao thức HTTP xác định một số phương thức gán ý nghĩa ngữ nghĩa cho một yêu cầu. Các phương thức HTTP phổ biến được hầu hết các API web RESTful sử dụng là:

- **GET** truy xuất dữ liệu đại diện của tài nguyên tại URI được chỉ định. Nội dung của thông báo phản hồi chứa các chi tiết của tài nguyên được yêu cầu.
- **POST** tạo một tài nguyên mới tại URI được chỉ định. Nội dung của thông báo yêu cầu cung cấp các chi tiết của tài nguyên mới. Lưu ý rằng POST cũng có thể được sử dụng để kích hoạt các hoạt động không thực sự tạo tài nguyên.
- **PUT** tạo hoặc thay thế tài nguyên tại URI được chỉ định. Nội dung của thông báo yêu cầu chỉ định tài nguyên sẽ được tạo hoặc cập nhật.
- **PATCH** thực hiện cập nhật một phần tài nguyên. Nội dung yêu cầu chỉ định tập hợp các thay đổi để áp dụng cho tài nguyên.
- **DELETE** xóa bỏ tài nguyên tại URI được chỉ định.

Hiệu quả của một yêu cầu cụ thể sẽ phụ thuộc vào việc tài nguyên là một bộ sưu tập hay một mục riêng lẻ. Bảng sau đây tóm tắt các quy ước chung được hầu hết các triển khai RESTful áp dụng bằng cách sử dụng ví dụ thương mại điện tử. Không phải tất cả các yêu cầu này đều có thể được thực hiện — nó phụ thuộc vào tình huống cụ thể.

| Resource            | POST                              | GET                                   | PUT                                                | DELETE                               |
|---------------------|-----------------------------------|---------------------------------------|----------------------------------------------------|--------------------------------------|
| /customers          | Tạo mới một customer              | Lấy tất cả các customers              | Cập nhật hàng loạt customers                       | Xóa tất cả customers                 |
| /customers/1        | Error                             | Lấy thông tin chi tiết cho customer 1 | Cập nhật thông tin chi tiết customer 1 nếu tồn tại | Xóa customer 1                       |
| /customers/1/orders | Tạo đơn hàng mới cho customer 1 | Lấy tất cả các orders của customer 1  | Cập nhật hàng loạt orders của customer 1           | Xóa tất cà các orders của customer 1 |

Sự khác biệt giữa POST, PUT và PATCH có thể gây nhầm lẫn.

- **POST** sẽ tạo ra một tài nguyên. Máy chủ chỉ định một URI cho tài nguyên mới và trả lại URI đó cho máy khách. Trong mô hình REST, bạn thường xuyên áp dụng các yêu cầu POST cho các bộ sưu tập. Tài nguyên mới được thêm vào bộ sưu tập. **POST** cũng có thể được sử dụng để gửi dữ liệu để xử lý đến một tài nguyên hiện có mà không cần tạo bất kỳ tài nguyên mới nào.
- Một yêu cầu **PUT** tạo một tài nguyên hoặc cập nhật một tài nguyên hiện có. Máy khách chỉ định URI cho tài nguyên. Nội dung yêu cầu chứa một bản trình bày đầy đủ của tài nguyên. Nếu một tài nguyên có URI này đã tồn tại, nó sẽ được thay thế. Nếu không, một tài nguyên mới sẽ được tạo, nếu máy chủ hỗ trợ làm như vậy. Yêu cầu **PUT** được áp dụng thường xuyên nhất cho các tài nguyên là các mục riêng lẻ, chẳng hạn như một khách hàng cụ thể, thay vì các bộ sưu tập. Máy chủ có thể hỗ trợ cập nhật nhưng không hỗ trợ tạo qua **PUT**. Việc có hỗ trợ tạo qua **PUT** hay không phụ thuộc vào việc máy khách có thể gán một cách có ý nghĩa một URI cho một tài nguyên trước khi nó tồn tại hay không. Nếu không, hãy sử dụng POST để tạo tài nguyên và **PUT** hoặc **PATCH** để cập nhật.
- Yêu cầu **PATCH** thực hiện cập nhật một phần tài nguyên hiện có. Máy khách chỉ định URI cho tài nguyên. Nội dung yêu cầu chỉ định một tập hợp các thay đổi để áp dụng cho tài nguyên. Điều này có thể hiệu quả hơn so với sử dụng **PUT**, bởi vì máy khách chỉ gửi các thay đổi, không phải toàn bộ biểu diễn của tài nguyên. Về mặt kỹ thuật, **PATCH** cũng có thể tạo một tài nguyên mới (bằng cách chỉ định một tập hợp các bản cập nhật cho tài nguyên "null"), nếu máy chủ hỗ trợ điều này.

Các yêu cầu **PUT** phải là không quan trọng. Nếu một khách hàng gửi cùng một yêu cầu **PUT** nhiều lần, kết quả phải luôn giống nhau (cùng một tài nguyên sẽ được sửa đổi với các giá trị giống nhau). Yêu cầu **POST** và **PATCH** không được đảm bảo là không cần thiết.

## Ngữ nghĩa HTTP

### Media types

Như đã đề cập trước đó, máy khách và máy chủ trao đổi các đại diện của tài nguyên. Ví dụ, trong một yêu cầu POST, nội dung yêu cầu chứa một bản đại diện của tài nguyên cần tạo. Trong một yêu cầu GET, phần thân phản hồi chứa một bản trình bày của tài nguyên đã được tìm nạp.

Trong giao thức HTTP, các định dạng được chỉ định thông qua việc sử dụng các loại phương tiện, còn được gọi là các loại MIME. Đối với dữ liệu không phải nhị phân, hầu hết các API web đều hỗ trợ JSON (media type = application/json) và có thể là XML (media type = application/xml).

 Header chứa Content-Type trong một yêu cầu hoặc phản hồi chỉ định định dạng của response. Dưới đây là một ví dụ về yêu cầu POST bao gồm dữ liệu JSON:

```http
POST <https://adventure-works.com/orders> HTTP/1.1
Content-Type: application/json; charset=utf-8
Content-Length: 57

{"Id":1,"Name":"Gizmo","Category":"Widgets","Price":1.99}
```

Nếu máy chủ không hỗ trợ loại phương tiện, nó sẽ trả về status code HTTP 415 (Media Type không được hỗ trợ).

Yêu cầu máy khách có thể bao gồm `Accept header` chứa danh sách các loại `media type` mà máy khách sẽ chấp nhận từ máy chủ trong thông báo phản hồi. Ví dụ:

```http
GET https://adventure-works.com/orders/2 HTTP/1.1
Accept: application/json
```

Nếu máy chủ không thể khớp với bất kỳ (các) loại phương tiện nào được liệt kê, nó sẽ trả về HTTP status code 406(Not Acceptable)

### Phương thức GET

Phương thức GET thành công thường trả về mã trạng thái HTTP 200 (OK). Nếu không tìm thấy tài nguyên, phương thức sẽ trả về 404 (Không tìm thấy).

Nếu yêu cầu đã được thực hiện nhưng không có nội dung phản hồi nào được bao gồm trong phản hồi HTTP, thì nó sẽ trả về mã trạng thái HTTP 204 (Không có nội dung)/
Ví dụ: với hoạt động tìm kiếm không có kết quả phù hợp nào có thể được triển khai với hành vi này.

### Phương thức POST

Nếu một phương thức POST tạo một tài nguyên mới, nó sẽ trả về mã trạng thái HTTP 201 (Đã tạo). URI của tài nguyên mới được bao gồm trong tiêu đề Vị trí của phản hồi. Phần nội dung phản hồi chứa một bản đại diện của tài nguyên.

Nếu phương thức thực hiện một số xử lý nhưng không tạo tài nguyên mới, phương thức có thể trả về mã trạng thái HTTP 200 và bao gồm kết quả của hoạt động trong phần thân phản hồi. Ngoài ra, nếu không có kết quả nào để trả về, phương thức có thể trả về mã trạng thái HTTP 204 (Không có Nội dung) mà không có nội dung phản hồi.

Nếu máy khách đưa dữ liệu không hợp lệ vào yêu cầu, máy chủ sẽ trả về mã trạng thái HTTP 400 (Yêu cầu không hợp lệ). Nội dung phản hồi có thể chứa thông tin bổ sung về lỗi hoặc liên kết đến URI cung cấp thêm chi tiết.

### Phương thức PUT

Nếu một phương thức PUT tạo một tài nguyên mới, nó sẽ trả về mã trạng thái HTTP 201 (Đã tạo), giống như với một phương thức POST. Nếu phương thức cập nhật tài nguyên hiện có, nó sẽ trả về 200 (OK) hoặc 204 (Không có nội dung). Trong một số trường hợp, có thể không cập nhật được tài nguyên hiện có. Trong trường hợp đó, hãy xem xét trả lại mã trạng thái HTTP 409 (Xung đột).

Xem xét triển khai các hoạt động HTTP PUT hàng loạt có thể cập nhật hàng loạt cho nhiều tài nguyên trong một bộ sưu tập. Yêu cầu PUT phải chỉ định URI của tập hợp và cơ quan yêu cầu phải chỉ định chi tiết của các tài nguyên sẽ được sửa đổi. Cách tiếp cận này có thể giúp giảm bớt sự tán gẫu và cải thiện hiệu suất.

### Phương thức PATCH

Với một yêu cầu PATCH, máy khách sẽ gửi một tập hợp các bản cập nhật cho một tài nguyên hiện có, dưới dạng một tài liệu vá lỗi. Máy chủ xử lý tài liệu bản vá để thực hiện cập nhật. Tài liệu vá lỗi không mô tả toàn bộ tài nguyên, chỉ một tập hợp các thay đổi để áp dụng. Đặc tả cho phương thức PATCH ([RFC 5789](https://tools.ietf.org/html/rfc5789)) không xác định một định dạng cụ thể cho các tài liệu vá lỗi. Định dạng phải được suy ra từ loại phương tiện trong yêu cầu.

JSON có lẽ là định dạng dữ liệu phổ biến nhất cho các web API. Có 2 loại JSON chính là:  `JSON patch` và `JSON merge patch`.
`JSON merge patch` có phần đơn giản hơn, nó có cùng cấu trúc với JSON ban đầu, nhưng chỉ bao gồm tập con của các trường cần được thay đổi hoặc thêm vào. Ngoài ra, một trường có thể bị xóa bằng cách chỉ định null cho giá trị trong dữ liệu.

Ví dụ: giả sử tài nguyên gốc có cấu trúc JSON sau:

```json
{
    "name":"gizmo",
    "category":"widgets",
    "color":"blue",
    "price":10
}
```

Đây là bản JSON merge patch có thể có cho tài nguyên này:

```json
{
    "price":12,
    "color":null,
    "size":"small"
}
```

Điều này cho máy chủ cập nhật `price`, xóa `color` và thêm `size`, trong khi `name` và `category` không được sửa đổi. Để biết chi tiết chính xác về `JSON merge patch`, hãy xem [RFC 7396](https://tools.ietf.org/html/rfc7396). Loại phương tiện cho bản vá hợp nhất JSON là `application/merge-patch+json`.

`Merge patch` không phù hợp nếu tài nguyên gốc có thể chứa các giá trị null, do ý nghĩa đặc biệt của null trong `patch document`. Ngoài ra, `patch document` không chỉ định thứ tự máy chủ sẽ áp dụng các bản cập nhật.
`JSON patch` được định nghĩa trong [RFC 6902](https://tools.ietf.org/html/rfc6902), linh hoạt hơn. Nó chỉ định các thay đổi như một chuỗi các hoạt động để áp dụng. Các hoạt động bao gồm thêm, xóa, thay thế, sao chép và kiểm tra (để xác thực các giá trị). Media type cho `JSON patch` là `application/json-patch+json`.

Dưới đây là một số điều kiện lỗi điển hình có thể gặp phải khi xử lý yêu cầu PATCH, cùng với HTTP status code thích hợp.

| Error condition                                                                                     | HTTP status code             |
|-----------------------------------------------------------------------------------------------------|------------------------------|
| Định dạng `patch document` không được hỗ trợ.                                                       | 415 (Unsupported Media Type) |
| `patch document` không đúng định dạng.                                                              | 400 (Bad Request)            |
| `patch document` hợp lệ, nhưng không thể áp dụng các thay đổi cho tài nguyên ở trạng thái hiện tại. | 409 (Conflict)               |

### Phương thức DELETE

Nếu thao tác xóa thành công, máy chủ web sẽ phản hồi với mã trạng thái HTTP 204 (Không có Nội dung), cho biết rằng quá trình đã được xử lý thành công, nhưng nội dung phản hồi không chứa thêm thông tin. Nếu tài nguyên không tồn tại, máy chủ web có thể trả về HTTP 404 (Không tìm thấy).

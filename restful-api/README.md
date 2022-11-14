# RESTful web API design


Dưới đây là một số nguyên tắc thiết kế chính của RESTful API:

- Các API REST được thiết kế xung quanh các tài nguyên, là bất kì loại đối tượng, dữ liệu hoặc dịch vụ nào mà client có thể truy cập.
- Một tài nguyên có một định danh, là một URI xác định duy nhất tài nguyên đó. Ví dụ: URI cho một đơn đặt hàng của khách hàng cụ thể có thể là:

```text
https://adventure-works.com/orders/1
```

- Client tương tác với một dịch vụ bằng cách trao đổi các đại diện của tài nguyên. Nhiều API web sự dung JSON làm định dạng tra đổi. Ví dụ một yêu cầu GET tới URI được liệt kê ở trên có thể trả về nội dung phản hồi này:
```json
{"orderId":1,"orderValue":99.90,"productId":1,"quantity":1}
```
- Các API REST sử dụng một giao diện thống nhất, giúp phân tách việc triển khai máy khách và dịch vụ. Đối với các API REST được xây dựng trên HTTP, giao diện thống nhất bao gồm việc sử dụng các động từ HTTP tiêu chuẩn để thực hiện các hoạt động trên tài nguyên. Các thao tác phổ biến nhất là **GET**, **POST**, **PUT**, **PATCH** và **DELETE**.
- Các API REST sử dụng mô hình yêu cầu không trạng thái. Các yêu cầu HTTP phải độc lập và có thể xảy ra theo bất kỳ thứ tự nào, vì vậy việc lưu giữ thông tin trạng thái tạm thời giữa các yêu cầu là không khả thi. Nơi duy nhất mà thông tin được lưu trữ là trong chính các tài nguyên và mỗi yêu cầu phải là một hoạt động nguyên tử. Ràng buộc này cho phép các dịch vụ web có khả năng mở rộng cao, vì không cần phải giữ lại bất kỳ mối quan hệ nào giữa các máy khách và máy chủ cụ thể. Bất kỳ máy chủ nào cũng có thể xử lý bất kỳ yêu cầu nào từ bất kỳ máy khách nào. Điều đó nói rằng, các yếu tố khác có thể hạn chế khả năng mở rộng. Ví dụ: nhiều dịch vụ web ghi vào kho dữ liệu phụ trợ, có thể khó mở rộng quy mô. Để biết thêm thông tin về các chiến lược mở rộng quy mô kho dữ liệu, hãy xem Phân vùng dữ liệu theo chiều ngang, chiều dọc và chức năng.
- Các API REST được điều khiển bởi các hypermedia links có thông tin được trả về. Ví dụ: phần sau cho thấy dữ liệu được trả về dạng JSON của một đơn đặt hàng. Nó chứa các liên kết để lấy hoặc cập nhật khách hàng được liên kết với đơn đặt hàng.
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

# Organize the API design around resources

Tập trung vào các thực thể kinh doanh mà API web hiển thị. Ví dụ: trong hệ thống thương mại điện tử, các thực thể chính có thể là khách hàng và đơn đặt hàng. Việc tạo đơn hàng có thể đạt được bằng cách gửi một yêu cầu HTTP POST có chứa thông tin đơn hàng. Phản hồi HTTP cho biết đơn hàng đã được đặt thành công hay chưa. Khi có thể, các URI tài nguyên phải dựa trên danh từ (tài nguyên) chứ không phải động từ (các hoạt động trên tài nguyên).

```text
https://adventure-works.com/orders // Good

https://adventure-works.com/create-order // Avoid
```

Tài nguyên không nhất thiết phải dựa trên một mục dữ liệu vật lý. Ví dụ: một tài nguyên đơn hàng có thể được triển khai nội bộ dưới dạng một số bảng trong cơ sở dữ liệu quan hệ, nhưng được trình bày cho khách hàng dưới dạng một thực thể duy nhất. Tránh tạo các API chỉ phản ánh cấu trúc bên trong của cơ sở dữ liệu. Mục đích của REST là mô hình hóa các thực thể và các hoạt động mà một ứng dụng có thể thực hiện trên các thực thể đó. Khách hàng không nên tiếp xúc với việc triển khai nội bộ.

Các thực thể thường được nhóm lại với nhau thành các tập hợp (đơn đặt hàng, khách hàng). Bộ sưu tập là một tài nguyên riêng biệt với mục trong bộ sưu tập và phải có URI của riêng nó. Ví dụ: URI sau có thể đại diện cho tập hợp các đơn đặt hàng:

```text
https://adventure-works.com/orders
```

Gửi yêu cầu HTTP GET tới bộ sưu tập URI truy xuất danh sách các mục trong bộ sưu tập. Mỗi mục trong bộ sưu tập cũng có URI duy nhất của riêng nó. Yêu cầu HTTP GET tới URI của mặt hàng trả về thông tin chi tiết của mặt hàng đó.

Áp dụng quy ước đặt tên nhất quán trong URI. Nói chung, việc sử dụng danh từ số nhiều cho các URI tham chiếu các tập hợp sẽ rất hữu ích. Đó là một phương pháp hay để tổ chức các URI cho các bộ sưu tập và các mục thành một hệ thống phân cấp. Ví dụ: `/customers` là đường dẫn đến bộ sưu tập khách hàng và `/customers/5` là đường dẫn đến khách hàng có ID bằng 5. Cách tiếp cận này giúp giữ cho API web trực quan. Ngoài ra, nhiều khung API web có thể định tuyến các yêu cầu dựa trên các đường dẫn URI được tham số hóa, vì vậy bạn có thể xác định một tuyến đường cho đường dẫn `/customers/{id}`.

Cũng xem xét các mối quan hệ giữa các loại tài nguyên khác nhau và cách bạn có thể hiển thị các liên kết này. Ví dụ: `/customers/5/orders` có thể đại diện cho tất cả các đơn đặt hàng cho khách hàng 5. Bạn cũng có thể đi theo hướng khác và đại diện cho sự liên kết từ một đơn đặt hàng trở lại khách hàng với một URI chẳng hạn như `/orders/99/customers`. Tuy nhiên, việc mở rộng mô hình này quá xa có thể trở nên khó thực hiện. Giải pháp tốt hơn là cung cấp các liên kết có thể điều hướng đến các tài nguyên được liên kết trong nội dung của thông báo phản hồi HTTP. Cơ chế này được mô tả chi tiết hơn trong phần [sử dụng HATEOAS để cho phép điều hướng đến các tài nguyên liên quan](https://martinfowler.com/articles/richardsonMaturityModel.html).

Trong các hệ thống phức tạp hơn, có thể hấp dẫn để cung cấp các URI cho phép khách hàng điều hướng qua một số cấp độ của mối quan hệ, chẳng hạn như `/customers/1/orders/99/products`. Tuy nhiên, mức độ phức tạp này có thể khó duy trì và không linh hoạt nếu mối quan hệ giữa các nguồn lực thay đổi trong tương lai. Thay vào đó, hãy cố gắng giữ cho các URI tương đối đơn giản. Khi một ứng dụng có tham chiếu đến một tài nguyên, có thể sử dụng tham chiếu này để tìm các mục liên quan đến tài nguyên đó. Truy vấn trước có thể được thay thế bằng URI `/customers/1/orders` để tìm tất cả đơn hàng cho khách hàng 1 và sau đó `/orders/99/products` để tìm sản phẩm trong đơn hàng này.

> **_NOTE:_** Tránh yêu cầu các URI tài nguyên phức tạp hơn *collection/item/collection*.



Một yếu tố khác là tất cả các yêu cầu web đều áp đặt tải lên máy chủ web. Yêu cầu càng nhiều, tải càng lớn. Do đó, hãy cố gắng tránh các API web "chatty" làm lộ ra một số lượng lớn các tài nguyên nhỏ. Một API như vậy có thể yêu cầu ứng dụng khách gửi nhiều yêu cầu để tìm tất cả dữ liệu mà nó yêu cầu. Thay vào đó, bạn có thể muốn chuẩn hóa dữ liệu và kết hợp thông tin liên quan thành các tài nguyên lớn hơn có thể được truy xuất bằng một yêu cầu duy nhất. Tuy nhiên, bạn cần phải cân bằng phương pháp này so với chi phí tìm nạp dữ liệu mà khách hàng không cần. Việc truy xuất các đối tượng lớn có thể làm tăng độ trễ của một yêu cầu và phát sinh thêm chi phí băng thông. Để biết thêm thông tin về các phản vật chất hiệu suất này, hãy xem [Chatty I/O](https://learn.microsoft.com/en-us/azure/architecture/antipatterns/chatty-io/) và [Extraneous Fetching](https://learn.microsoft.com/en-us/azure/architecture/antipatterns/extraneous-fetching/).

Tránh giới thiệu sự phụ thuộc giữa API web và các nguồn dữ liệu cơ bản. Ví dụ: nếu dữ liệu của bạn được lưu trữ trong cơ sở dữ liệu quan hệ, thì web API không cần hiển thị mỗi bảng dưới dạng tập hợp tài nguyên. Trên thực tế, đó có lẽ là một thiết kế kém. Thay vào đó, hãy nghĩ về API web như một phần trừu tượng của cơ sở dữ liệu. Nếu cần, hãy giới thiệu một lớp ánh xạ giữa cơ sở dữ liệu và API web. Bằng cách đó, các ứng dụng khách được cách ly khỏi những thay đổi đối với lược đồ cơ sở dữ liệu bên dưới.

Cuối cùng, có thể không ánh xạ mọi hoạt động được thực hiện bởi API web tới một tài nguyên cụ thể. Bạn có thể xử lý các tình huống phi tài nguyên như vậy thông qua các yêu cầu HTTP gọi một hàm và trả về kết quả dưới dạng thông báo phản hồi HTTP. Ví dụ: một API web triển khai các hoạt động máy tính đơn giản như cộng và trừ có thể cung cấp các URI hiển thị các hoạt động này dưới dạng tài nguyên giả và sử dụng chuỗi truy vấn để chỉ định các tham số được yêu cầu. Ví dụ: một yêu cầu GET tới URI */add?operand1=99&operand2=1* sẽ trả về một thông báo phản hồi với phần nội dung chứa giá trị 100. Tuy nhiên, chỉ sử dụng các dạng URI này một cách tiết kiệm.

# Xác định các hoạt động API theo các phương thức HTTP

Giao thức HTTP xác định một số phương thức gán ý nghĩa ngữ nghĩa cho một yêu cầu. Các phương thức HTTP phổ biến được hầu hết các API web RESTful sử dụng là:

- **GET** truy xuất dữ liệu đại diện của tài nguyên tại URI được chỉ định. Nội dung của thông báo phản hồi chứa các chi tiết của tài nguyên được yêu cầu.
- **POST** tạo một tài nguyên mới tại URI được chỉ định. Nội dung của thông báo yêu cầu cung cấp các chi tiết của tài nguyên mới. Lưu ý rằng POST cũng có thể được sử dụng để kích hoạt các hoạt động không thực sự tạo tài nguyên.
- **PUT** tạo hoặc thay thế tài nguyên tại URI được chỉ định. Nội dung của thông báo yêu cầu chỉ định tài nguyên sẽ được tạo hoặc cập nhật.
- **PATCH** thực hiện cập nhật một phần tài nguyên. Nội dung yêu cầu chỉ định tập hợp các thay đổi để áp dụng cho tài nguyên.
- **DELETE**  xóa bỏ tài nguyên tại URI được chỉ định.

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
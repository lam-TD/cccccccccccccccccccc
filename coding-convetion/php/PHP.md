
# Coding Convention là gì?

Là tập hợp những nguyên tắc chung khi lập trình (được công nhận và đi theo bởi đa số các lập trình viên trên thế giới) nhằm làm cho code dễ đọc, dễ hiểu, từ đó dễ quản lý, phát triển hơn.

Một số quy tắc:

- Quy tắc đặt tên:
  - `camelCase`: ký tự đầu tiên của từ đầu tiên được viết thường, các ký tự đầu tiên của các từ còn lại được viết in hoa. Quy tắc này thường được áp dụng cho tên hàm, tên biến. Ví dụ: $camelCase, $userName, camelCase(), updateUser(), ,...
  - `snake_case`: cú pháp này còn được gọi là cú pháp rắn chi thuật, tất cả các ký tự đều viết thường và các từ cách nhau bởi dấu `_`. Quy tắc này thường được áp dụng cho tên hàm, tên biến. Ví dụ: $snake_case, $user_name, snake_case(), update_user(),...
  - `StudlyCaps`: các ký tự đầu tiên của mỗi từ sẽ được viết hoa. Quy tắc này thường được áp dụng cho tên lớp. Ví dụ: `UserController`,...
  - Tên biến thường là danh từ hoặc cụm danh từ: `$users`, `$userName`,...
  - Tên hàm thường bắt đầu bằng một động từ: `getUsers()`, `delete()`,...
- Quy tắc xuống dòng:
  - Nếu có dấu phẩy thì xuống hàng sau dấu phẩy
  - Nếu có nhiều cấp lồng nhau, thì xuống hàng theo từng cấp
  - Dòng xuống hàng mới thì nên bắt đầu ở cùng cột với đoạn lệnh cùng cấp ở trên.

Trên đây là một số quy tắc mà mình đã tìm hiểu được. Tiếp đến chúng ta sẽ tìm hiểu cụ thể hơn các quy tắc dựa vào các tiêu chuẩn được nhiều lập trình viên áp dụng đó là `PSR`.

# Giới thiệu về PSR

**PSR (PHP Standards Recommendation)** là tập hợp các quy tắc khi lập trình với ngôn ngữ PHP. Chuẩn này có các mức khác nhau, tùy vào từng môi trường làm việc sẽ yêu cầu những mức PSR khác nhau, trong khuôn khổ bài viết này mình sẽ cùng tìm hiểu về các chuẩn sau: `PSR-1` và `PSR-12`

## Chuẩn PSR-1: Đây là tiêu chuẩn code cơ bản

Chuẩn này bao gồm những yếu tố cơ bản được đòi hỏi để có thể đảm bảo tính tương kết giữa code PHP được chia sẻ.

### Files

1. PHP tags

PHP code phải sử dụng tag đầy đủ `<?php ?>` hoặc short-echo `<?= ?>` tags. Ngoài ra không được sử dụng những tag thay đổi khác. (Không dùng tag `<? ?>`)

2. Character Encoding

PHP code phải được encode bằng UTF-8 không có BOM.

3. Side Effects

Một tệp NÊN khai báo các ký hiệu mới (lớp, hàm, hằng số, v.v.) và không gây ra tác dụng phụ nào khác hoặc nó NÊN thực thi logic với các `side effects`, nhưng KHÔNG NÊN thực hiện cả hai.

Cụm từ `side effects` mang ý nghĩa là thực hiện những logic mà không liên quan trực tiếp đến việc định nghĩa classes, functions, constants ... thường là từ việc including file.

"Side effects" bao gồm những việc sau (không phải là tất cả): tạo output, sử dụng `require` `include`, hoặc kết nối đến external services, thay đổi file ini setting, emit errors hay exceptions, chỉnh sửa biến `global` hay `static`, đọc và ghi file ...

Dưới đây là một ví dụ về việc một file chứa cả declarations (định nghĩa) và side effects, một ví dụ về những thứ cần tránh:

```php
<?php
// side effect: change ini settings
ini_set('error_reporting', E_ALL);

// side effect: loads a file
include "file.php";

// side effect: generates output
echo "<html>\n";

// declaration
function foo()
{
    // function body
}
```

Ví dụ sau đây là một tệp chứa các khai báo không có `side effects`; tức là, một ví dụ về những gì cần mô tả:

```php
<?php
// declaration
function foo()
{
    // function body
}

// conditional declaration is *not* a side effect
if (! function_exists('bar')) {
    function bar()
    {
        // function body
    }
}
```

### Namespace and Class Names

Namespaces và tên classes phải tuân theo quy chuẩn "autoloading" của PSR: [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md), [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md).

Điều này có nghĩa là mỗi class phải được viết vào một file, và phải có ý nhất 1 level trong namespace.

Tên Class phải được viết dưới dạng `PascalCase`.

Code với phiên bản PHP 5.3 trở lên phải dùng đúng namespaces.

```php
<?php
// PHP 5.3 and later:
namespace Vendor\Model;

class Foo
{
}
```

### Class Constants, Properties, and Methods

Tên constants của Class phải được viết dưới dạng `ALL_UPPER_CASE_WITH_UNDERSCORE`.

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

Bộ quy tắc này không đưa ra quy định hay gợi ý về việc nên viết properties như thế nào, theo dạng `$StudlyCaps`, `$camelCase`, hay `$under_score`. Dù sử dụng quy tắc đặt tên nào đi chăng nữa thì nó cần phải được thực hiện thống nhất trong một vendor, package, class, method.

Methods

Các methods(tên hàm) phải được viết dưới dạng `camelCase()`.

## Chuẩn PSR2 và PSR-12: Phong cách code(Coding Style)

Chuẩn [PSR-2](https://www.php-fig.org/psr/psr-2/) đã được thay thế bằng chuẩn [PSR-12](https://www.php-fig.org/psr/psr-12/).

- Điều kiện tiên quyết là phải đạt chuẩn PSR-1.

Ví dụ dưới đây mô tả một số quy tắc dưới dạng tổng quan nhanh:

```php
<?php

declare(strict_types=1);

namespace Vendor\Package;

use Vendor\Package\{ClassA as A, ClassB, ClassC as C};
use Vendor\Package\SomeNamespace\ClassD as D;

use function Vendor\Package\{functionA, functionB, functionC};

use const Vendor\Package\{ConstantA, ConstantB, ConstantC};

class Foo extends Bar implements FooInterface
{
    public function sampleFunction(int $a, int $b = null): array
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // method body
    }
}
```

1. Basic coding standard

Code PHẢI tuân theo tất cả các quy tắc trong `PSR-1`.

Thuật ngữ `StudlyCaps` trong `PSR-1` PHẢI được hiểu là `PascalCase` trong đó chữ cái đầu tiên của mỗi từ được viết hoa bao gồm cả chữ cái đầu tiên.

2. Files

- Tất cả các file PHP phải kết thúc bằng một dòng không chứa khoảng trắng, được kết thức bằng 1 LF duy nhất.
- Thẻ đóng `?>` phải được bỏ qua nếu file chỉ chứa code PHP.

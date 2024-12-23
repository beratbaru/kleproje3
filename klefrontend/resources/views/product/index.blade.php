@extends('layouts.frontend')
    @section('content')
    @if(!session('api_token'))
    <div class="d-flex justify-content-center align-items-center vh-100" style="color:red; background-color:black;">
        <div class="text-center">
            <h3>Verileri görmek için giriş yapınız.</h3>
            <div class="mt-4">
                <a href="{{ route('register') }}" class="btn btn-danger me-2" style="color:pink; text-decoration:none;">Kayıt Ol</a>
                <a href="{{ route('login') }}" class="btn btn-secondary" style="color:pink; text-decoration:none;">Giriş Yap</a>
            </div>
        </div>
    </div>
    @else
    
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h4>Ürün Listesi</h4>
            <div class="d-flex align-items-center">
                <!-- Hamburger menu for smaller screens -->
                <div class="d-block d-md-none">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Menü
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Çıkış Yap</button>
                                </form>
                            </li>
                            <li><a class="dropdown-item" href="{{ url('product/create') }}">Ürün Ekle</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Buttons for larger screens -->
                <div class="d-none d-md-flex">
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger me-2">Çıkış Yap</button>
                    </form>
                    <a href="{{ url('product/create') }}" class="btn btn-secondary">Ürün Ekle</a>
                </div>
            </div>
        </div>
        
        @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Ürün Adı</th>
                        <th>Ürün Açıklaması</th>
                        <th>Fiyat</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($products) || count($products) === 0)
                    <tr>
                        <td colspan="5" class="text-center">Ürün bulunamadı.</td>
                    </tr>
                    @else
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product['id'] }}</td>
                        <td>{{ $product['product_name'] }}</td>
                        <td>
                            <div class="description" id="desc-{{ $product['id'] }}">
                                {{ $product['description'] }}
                            </div>
                            <a href="javascript:void(0)" class="read-more" data-id="{{ $product['id'] }}">...</a>
                        </td>
                        <td>{{ $product['product_price'] }}₺</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-around product-actions align-items-center">
                                <a href="{{ route('product.edit', $product['id']) }}" class="btn btn-success btn-sm w-100 w-md-auto">Düzenle</a>
                                <a href="{{ route('product.show', $product['id']) }}" class="btn btn-info btn-sm w-100 w-md-auto">Görüntüle</a>
                                <form action="{{ route('product.destroy', $product['id']) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');" class="w-100 w-md-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">Sil</button>
                                </form>
                            </div>
                        </td>
                        
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        
        @if (!empty($paginationLinks) && isset($totalProducts) && $totalProducts > 10)
        <div class="d-flex justify-content-center mt-4 align-items-center">
            <!-- Previous Page Link -->
            @if ($paginationLinks['previous'])
            <a href="{{ url()->current() . '?' . parse_url($paginationLinks['previous'], PHP_URL_QUERY) }}" class="btn btn-secondary mx-1">Geri</a>
            @else
            <button class="btn btn-secondary mx-1" disabled>Geri</button>
            @endif
            
            <!-- Page Counter -->
            @if (isset($currentPage) && isset($totalPages))
            <span class="mx-3">Sayfa {{ $currentPage }} / {{ $totalPages }}</span>
            @endif
            
            <!-- Next Page Link -->
            @if ($paginationLinks['next'])
            <a href="{{ url()->current() . '?' . parse_url($paginationLinks['next'], PHP_URL_QUERY) }}" class="btn btn-secondary mx-1">İleri</a>
            @else
            <button class="btn btn-secondary mx-1" disabled>İleri</button>
            @endif
        </div>
        @endif
    </div>

    <script>
        document.querySelectorAll('.description').forEach(desc => {
            const lineHeight = parseFloat(getComputedStyle(desc).lineHeight);
            const maxHeight = lineHeight * 2;

            if (desc.scrollHeight > maxHeight) {
                const readMoreLink = desc.nextElementSibling;
                readMoreLink.style.display = 'inline';
            }
        });

        document.querySelectorAll('.read-more').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const desc = document.getElementById( `desc-${id} `);
                desc.classList.toggle('expanded');

                if (desc.classList.contains('expanded')) {
                    desc.style.maxHeight = 'none';
                    this.innerText = 'Daha Az Göster';
                } else {
                    desc.style.maxHeight = '';
                    this.innerText = '...';
                }
            });
        });
    </script>
@endif
@endsection
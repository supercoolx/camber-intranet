<header class="fixed-top">
 <div class="container">
    <h6 class="text-light pl-3 pr-3 mb-0 pt-1 d-flex justify-content-start">
        @foreach ($coinsInHeader as $coin)
          <span class="text-white" title="{{ $coin['name'] }}">
            <img class="rates-icon" src='{{ $coin['icon'] }}' > ${{ $coin['rate'] }} &nbsp;&nbsp;
          </span>
        @endforeach
    </h6>

<nav class="navbar navbar-expand-lg pb-0 pt-0 flex-nowrap">
  <a class="navbar-brand" href="/">Calculator</a>
  <div class="d-flex align-self-end w-100" id="navbarSupportedContent">
    <ul class="nav nav-tabs mr-auto">
      <li class="nav-item {{ Request::is('gpu') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('gpu') ? 'active' : '' }}" href="/gpu">GPU</a>
      </li>
      <li class="nav-item pl-1 {{ Request::is('asic') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('asic') ? 'active' : '' }}" href="/asic">ASIC</a>
      </li>
      <li class="nav-item pl-1 {{ Request::is('coins') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('coins') ? 'active' : '' }}" href="/coins">Coins</a>
      </li>
      <li class="nav-item pl-1 dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="eth-menu-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          ETH+
        </a>
        <div class="dropdown-menu" aria-labelledby="eth-menu-dropdown">
            @foreach ($dualMiningMenu['ETH'] as $firstCoin => $secondCoin)
              <a class="dropdown-item" href="/merged_coins/ETH/{{$secondCoin}}">ETH+{{$secondCoin}}</a>
            @endforeach
        </div>
      </li>
      <li class="nav-item pl-1 dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="etc-menu-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          ETC+
        </a>
        <div class="dropdown-menu" aria-labelledby="etc-menu-dropdown">
          @foreach ($dualMiningMenu['ETC'] as $firstCoin => $secondCoin)
            <a class="dropdown-item" href="/merged_coins/ETC/{{$secondCoin}}">ETC+{{$secondCoin}}</a>
          @endforeach
        </div>
      </li>
      <li class="nav-item pl-1 dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="exp-menu-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          EXP+
        </a>
        <div class="dropdown-menu" aria-labelledby="exp-menu-dropdown">
          @foreach ($dualMiningMenu['EXP'] as $firstCoin => $secondCoin)
            <a class="dropdown-item" href="/merged_coins/EXP/{{$secondCoin}}">EXP+{{$secondCoin}}</a>
          @endforeach
        </div>
      </li>
    </ul>
    <ul class="nav nav-tabs border-0 justify-content-end">
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">JSON</a>
        </li> -->
        <li class="nav-item pl-1 {{ Request::is('contacts') ? 'active' : '' }}">
          <a class="nav-link {{ Request::is('contacts') ? 'active' : '' }}" href="/contacts">Contact</a>
        </li>
    </ul>
  </div>
</nav>
</div>
@if(Request::is('gpu') || Request::is('asic'))
<div class="container-fluid list-preset blank">
 <div class="container">
    <div class="row pl-3 pr-3">
        <div class="col-md-9 pr-0 d-flex align-items-end">
           <ul id="listPreset" class="nav nav-tabs border-0"></ul>
        </div>
        <div class="col-md-3 input-group pt-1 pb-1 pl-0">
               <input type="text" name="dataset" id="dataset" class="form-control" placeholder="Add new dataset">
               <div class="input-group-append">
                       <input type="submit" name="commit" value="Add" class="btn btn-outline-secondary" data-disable-with="Add">
               </div>
        </div>
    </div>
 </div>
</div>
@endif
</header>



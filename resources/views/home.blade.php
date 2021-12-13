@extends('layouts.app')

@section('content')
<style>
    .custom-combobox {
      position: relative;
      display: inline-block;
      width:100%;
    }
    .custom-combobox-toggle {
      position: absolute;
      top: 0;
      bottom: 0;
      margin-left: -1px;
      padding: 0;
    }
    .custom-combobox-input {
      margin: 0;
      padding: 5px 10px;
    }

    .ui-autocomplete-input
    {
        width: 435px;
    }
    /* .title .ui-autocomplete-input
    {
        width: 200px;
    }
    .tags .ui-autocomplete-input
    {
        width: 600px;
    } */
  </style>
<div class="container-fluid pt-3 d-grid" style="grid-template-rows:1fr;">
    <div class="row">
        <div class="col-md-3 h-100">
            <ul class="nav d-flex flex-column justify-content-start px-3 h-100">
                <li class="nav-item mb-2">
                  <button class="btn btn-property w-101 {{ Request::is('listings') ? 'active' : '' }}" data-toggle="modal" data-target="#ModalListings">Listings</button>
                </li>
             
             
                <li class="nav-item mb-2">
                  <button class="btn btn-property w-101" data-toggle="modal" data-target="#ModalStartContract">Start Buy/Sell Contract</button>
                </li>

                <li class="nav-item mb-2">
                  <button class="btn btn-property w-101" data-toggle="modal" data-target="#ModalReserveConferenceRoom">Reserve Conference Room</button>
                </li>

                <li class="nav-item mb-2">
                  <button class="btn btn-property w-101" data-toggle="modal" data-target="#ModalRequestSocialMediaPost">Request Social Media Post</button>
                </li>

                <li class="nav-item mb-2">
                  <button class="btn btn-property w-101" data-toggle="modal" data-target="#ModalRequestTour">Wednesday Tour Request</button>
                </li>

                   <li class="nav-item mb-2">
                  <button class="btn btn-property w-101" data-toggle="modal" data-target="#ModalOrderClientGift">Order Client Gift</button>
                </li>

                <li class="nav-item mb-2">
                  <button class="btn btn-property w-101" data-toggle="modal" data-target="#ModalBuyerRepSign">Buyer Rep Sign</button>
                </li>
          
                <li class="nav-item mb-2">
                  <button class="btn btn-property w-101" data-toggle="modal" data-target="#ModalVendorList">Edit Vendor List</button>
                </li>


         
            </ul>
        </div>
        <div class="col-md-6">
            <div class="row">
              <div class="col-12">
 <iframe name="calendar" src="https://calendar.google.com/calendar/embed?title=Camber%20Office&showTitle=0&showNav=1&showPrint=0&showCalendars=0&height=600&wkst=1&bgcolor=%23ffffff&src=camberrealty.com_09ons2dptif26pdcvhpih9fhvc%40group.calendar.google.com&color=%23B1440E&src=en.usa%23holiday%40group.v.calendar.google.com&color=%230F4B38&ctz=America%2FDenver" style="border-width:0" width="100%" height="480" frameborder="0" scrolling="no"></iframe>

<!--            <iframe src="https://calendar.google.com/calendar/b/1/embed?height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;ctz=America%2FDenver&amp;src=bWFya0BjYW1iZXJyZWFsdHkuY29t&amp;src=Y2FtYmVycmVhbHR5LmNvbV8wOW9uczJkcHRpZjI2cGRjdmhwaWg5Zmh2Y0Bncm91cC5jYWxlbmRhci5nb29nbGUuY29t&amp;src=Y2FtYmVycmVhbHR5LmNvbV82MDUxMDljNWZkY2FjNzU2ZjE4NDU0MDUxZDA2NDQ5MTdhN2Q4Nzg0NjYyNTM1OTI4M2Q0ZTZlNGUxYThmMDZiQGdyb3VwLmNhbGVuZGFyLmdvb2dsZS5jb20&amp;src=ZW4udXNhI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&amp;color=%230BBCB2&amp;color=%23FF7537&amp;color=%23D21E5B&amp;color=%2350B68E" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>-->
<!--                  <iframe src="https://calendar.google.com/calendar/b/1/embed?height=600&wkst=1&bgcolor=%23ffffff&ctz=America%2FDenver&src=bWFya0BjYW1iZXJyZWFsdHkuY29t&src=Y2FtYmVycmVhbHR5LmNvbV8wOW9uczJkcHRpZjI2cGRjdmhwaWg5Zmh2Y0Bncm91cC5jYWxlbmRhci5nb29nbGUuY29t&src=Y2FtYmVycmVhbHR5LmNvbV82MDUxMDljNWZkY2FjNzU2ZjE4NDU0MDUxZDA2NDQ5MTdhN2Q4Nzg0NjYyNTM1OTI4M2Q0ZTZlNGUxYThmMDZiQGdyb3VwLmNhbGVuZGFyLmdvb2dsZS5jb20&src=ZW4udXNhI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&color=%ffca6600" style="border-width:0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>-->
<!--              <iframe src="https://calendar.google.com/calendar/b/1/embed?height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;ctz=America%2FDenver&amp;src=Y2FtYmVycmVhbHR5LmNvbV8wOW9uczJkcHRpZjI2cGRjdmhwaWg5Zmh2Y0Bncm91cC5jYWxlbmRhci5nb29nbGUuY29t&amp;src=Y2FtYmVycmVhbHR5LmNvbV82MDUxMDljNWZkY2FjNzU2ZjE4NDU0MDUxZDA2NDQ5MTdhN2Q4Nzg0NjYyNTM1OTI4M2Q0ZTZlNGUxYThmMDZiQGdyb3VwLmNhbGVuZGFyLmdvb2dsZS5jb20&amp;src=ZW4udXNhI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&amp;color=%23ca6600&amp;color=%23D21E5B&amp;color=%2350B68E&amp;showTitle=0&amp;showCalendars=1" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>-->
<!--                  <iframe name="calendar" src="https://calendar.google.com/calendar/embed?title=Camber%20Office&showTitle=0&showNav=0&showPrint=0&showCalendars=0&height=600&wkst=1&bgcolor=%23ffffff&src=camberrealty.com_09ons2dptif26pdcvhpih9fhvc%40group.calendar.google.com&color=%23B1440E&src=en.usa%23holiday%40group.v.calendar.google.com&color=%230F4B38&ctz=America%2FDenver" style="border-width:0" width="640" height="480" frameborder="0" scrolling="no"></iframe>-->
              </div>
            </div>
        </div>
        <div class="col-md-3 h-100">
            <ul class="nav d-flex flex-column justify-content-start px-3 h-100">
                
                <li class="nav-item mb-2">
                  <a class="btn btn-property w-101" target="_blank" href="{{ $camAccount }}">CAM Account</a>
                </li>
                <li class="nav-item mb-2">
                  <a class="btn btn-property w-101" target="_blank" href="https://drive.google.com/drive/folders/1l83ieVBuop5XHusI-gDtAdYRwCAT-Apa?usp=sharing">Listing Presentation</a>
                </li>
                <li class="nav-item mb-2">
                  <a class="btn btn-property w-101" target="_blank" href="https://drive.google.com/drive/folders/1C56r7laELRlUgFQAixN_F-MIc6Rc0rj2?usp=sharing">Exchange Notes</a>
                </li>
                <li class="nav-item mb-2">
                  <a class="btn btn-property w-101" target="_blank" href="https://drive.google.com/open?id=1MtxlK-qtQCrl-JvDEx_c_I-dHEARATUwwwXOl8bMhVE">Hot Sheet</a>
                </li>

                   <li class="nav-item mb-2">
                  <a class="btn btn-property w-101" target="_blank" href="https://drive.google.com/drive/folders/0B9ZraoldafAgX1E0ajN0RjdUTjQ?usp=sharing">Company Logo</a>
                </li>
                <li class="nav-item mb-2">
                  <a class="btn btn-property w-101" target="_blank" href="https://drive.google.com/file/d/192unnhV-HdQNzO0lebOGGOH9M4AJPnvl/view">Office Policy</a>
                </li>


                <li class="nav-item mb-2">
                  <a class="btn btn-property w-101" target="_blank" href="{{ route('agent.show', ['hash' => Auth::user()->getEncodeId()]) }}">Refer a Friend</a>
                </li>
                <li class="nav-item mb-2">
                  <a class="btn btn-property w-101" target="_blank" href="{{ route('agent.report', ['hash' => Auth::user()->getEncodeId()]) }}">Report</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Modal -->
@include('forms.listings')
@include('forms.reserveConferenceRoom')
@include('forms.buyerRepSign')
@include('forms.startContract')
@include('forms.requestTour')
@include('forms.orderClientGift')
@include('forms.vendorList')
@include('forms.requestSocialMediaPost')

@if($errors->any())
<script>
    $('#ModalCenter').show();
</script>
@endif

@endsection

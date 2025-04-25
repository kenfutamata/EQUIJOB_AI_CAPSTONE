<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Sign In</title>
</head>
<body>
<!--Nav Bar component-->
<x-landing-page-navbar/>

<div data-layer="Login page(A)" class="LoginPageA w-[1440px] h-[1024px] relative inline-flex justify-start items-start gap-48 flex-wrap content-start overflow-hidden">
  <div data-layer="sign in" class="SignIn w-[539px] h-[635px] left-[450.50px] top-[213px] absolute bg-white rounded-3xl outline outline-2 outline-offset-[-2px] outline-neutral-900">
    <div data-layer="Frame 377" class="Frame377 w-[459px] left-[40px] top-[80px] absolute inline-flex flex-col justify-start items-start gap-5">
      <div data-layer="Frame 375" class="Frame375 self-stretch flex flex-col justify-center items-start gap-8">
        <div data-layer="Sign in" class="SignIn text-center justify-center text-zinc-800 text-3xl font-medium font-['Poppins']">Sign in</div>
        <div data-layer="Email" class="Email w-[459px] flex flex-col justify-start items-start gap-1">
          <div data-layer="Frame 243" class="Frame243 self-stretch h-7 relative">
            <div data-layer="Label" class="Label left-0 top-0 absolute justify-start text-stone-500 text-base font-normal font-['Poppins']">Email or phone number</div>
          </div>
          <div data-layer="Text field" class="TextField self-stretch h-14 relative rounded-xl outline outline-1 outline-offset-[-1px] outline-stone-500/30"></div>
        </div>
        <div data-layer="Email" class="Email w-[459px] flex flex-col justify-start items-start gap-1">
          <div data-layer="Frame 243" class="Frame243 self-stretch h-7 relative">
            <div data-layer="Label" class="Label left-0 top-0 absolute justify-start text-stone-500 text-base font-normal font-['Poppins']">Password</div>
            <div data-svg-wrapper data-layer="icon" data-property-1="Hide" class="Icon left-[377.14px] top-[3px] absolute">
              <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20.5194 4.88128L19.7835 4.14533C19.5755 3.93734 19.1915 3.96935 18.9515 4.2573L16.3913 6.80126C15.2392 6.30532 13.9754 6.06532 12.6473 6.06532C8.69519 6.08126 5.27141 8.38523 3.62329 11.6974C3.52726 11.9054 3.52726 12.1614 3.62329 12.3373C4.39122 13.9054 5.54329 15.2014 6.9833 16.1773L4.8873 18.3053C4.6473 18.5453 4.61529 18.9293 4.77534 19.1373L5.51128 19.8732C5.71928 20.0812 6.10326 20.0492 6.34326 19.7613L20.3912 5.71339C20.6952 5.47352 20.7272 5.08956 20.5192 4.88155L20.5194 4.88128ZM13.4953 9.71316C13.2233 9.64914 12.9353 9.56919 12.6633 9.56919C11.3033 9.56919 10.2154 10.6572 10.2154 12.0171C10.2154 12.2891 10.2794 12.5771 10.3594 12.8491L9.28724 13.9051C8.96728 13.3452 8.7913 12.7211 8.7913 12.0172C8.7913 9.88918 10.5033 8.17715 12.6313 8.17715C13.3354 8.17715 13.9593 8.35314 14.5193 8.6731L13.4953 9.71316Z" fill="#666666" fill-opacity="0.8"/>
              <path d="M21.6714 11.6974C21.1115 10.5773 20.3754 9.56939 19.4635 8.75336L16.4875 11.6974V12.0173C16.4875 14.1453 14.7754 15.8573 12.6475 15.8573H12.3275L10.4395 17.7453C11.1436 17.8893 11.8795 17.9853 12.5995 17.9853C16.5516 17.9853 19.9754 15.6813 21.6235 12.3532C21.7675 12.1291 21.7675 11.9052 21.6715 11.6972L21.6714 11.6974Z" fill="#666666" fill-opacity="0.8"/>
              </svg>
            </div>
            <div data-layer="Hide" class="Hide left-[409.14px] top-0 absolute text-right justify-start text-stone-500/80 text-lg font-normal font-['Poppins']">Hide</div>
          </div>
          <div data-layer="Text field" class="TextField self-stretch h-14 relative rounded-xl outline outline-1 outline-offset-[-1px] outline-stone-500/30"></div>
        </div>
        <div data-layer="Frame 374" class="Frame374 size- flex flex-col justify-start items-start gap-2">
          <div data-layer="Frame 373" class="Frame373 size- inline-flex justify-end items-center gap-52">
            <div data-layer="Check box" data-property-1="Check box 1 line" class="CheckBox size- pr-2 py-2 flex justify-start items-start gap-2">
              <div data-svg-wrapper data-layer="Check box" data-property-1="Check box/check" class="CheckBox relative">
                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_907_1061)">
                <path d="M19.5 3H5.5C4.39 3 3.5 3.9 3.5 5V19C3.5 20.1 4.39 21 5.5 21H19.5C20.61 21 21.5 20.1 21.5 19V5C21.5 3.9 20.61 3 19.5 3ZM10.5 17L5.5 12L6.91 10.59L10.5 14.17L18.09 6.58L19.5 8L10.5 17Z" fill="#111111"/>
                </g>
                <defs>
                <clipPath id="clip0_907_1061">
                <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
                </clipPath>
                </defs>
                </svg>
              </div>
              <div data-layer="I want to receive emails about the product, feature updates, events, and marketing promotions." class="IWantToReceiveEmailsAboutTheProductFeatureUpdatesEventsAndMarketingPromotions justify-start text-zinc-800 text-base font-normal font-['Poppins']">Remember me</div>
            </div>
            <div data-layer="Have an account login" class="HaveAnAccountLogin size- p-0.5 flex justify-start items-start gap-2.5">
              <div data-layer="Already have an ccount? Log in" class="AlreadyHaveAnCcountLogIn text-center justify-start text-zinc-800 text-base font-normal font-['Poppins']">Need help?</div>
            </div>
          </div>
        </div>
      </div>
      <div data-layer="Button" data-property-1="Default" class="Button w-[459px] px-48 py-3.5 bg-red-600 rounded-[32px] flex flex-col justify-start items-start gap-2.5 overflow-hidden">
        <div data-layer="Frame 276" class="Frame276 self-stretch inline-flex justify-center items-center gap-2">
          <div data-layer="Sign up" class="SignUp text-center justify-center text-white text-lg font-normal font-['Poppins']">Sign in</div>
        </div>
      </div>
      <div data-layer="Login as Admin" class="LoginAsAdmin self-stretch justify-start text-zinc-800 text-base font-normal font-['Poppins'] underline">Login as Admin</div>
      <div data-layer="Frame 376" class="Frame376 size- flex flex-col justify-start items-start gap-2">
        <div data-layer="Have an account login" class="HaveAnAccountLogin size- p-0.5 inline-flex justify-start items-start gap-2.5">
          <div data-layer="Already have an ccount? Log in" class="AlreadyHaveAnCcountLogIn justify-start"><span class="text-stone-500 text-base font-normal font-['Poppins']">Don’t have an acount? </span><span class="text-neutral-900 text-base font-medium font-['Poppins'] underline">Sign up</span><span class="text-neutral-900 text-base font-normal font-['Poppins'] underline">  </span></div>
        </div>
      </div>
    </div>
    <div data-layer="Icons" data-property-1="Close" class="Icons size-6 left-[491px] top-[24px] absolute overflow-hidden">
      <div data-layer="Vector" class="Vector size-6 left-0 top-0 absolute"></div>
    </div>
    <div data-layer="Nav link comp" data-property-1="Nav link - Default" class="NavLinkComp size- left-[340px] top-[30px] absolute inline-flex justify-start items-start">
      <div data-layer="Nav link" class="NavLink justify-start"></div>
    </div>
  </div>
</div>
</body>
</html>
<script>
    var tutorial = introJs().setOptions({
        showStepNumbers: false,
        showProgress: false,
        showBullets: false,
        steps: [
            {
                intro: "Hey there, welcome to Pharmacy4U! Click Next to start your guided tour, or click Skip to jump straight in!",
                tooltipClass: "tooltip-wide"
            },
            {
                element: document.querySelector('.sidebar-menu-items a:nth-child(2)'),
                intro: "Easily manage your products",
                position: "right"
            },
            {
                element: document.querySelector('.sidebar-menu-items a:nth-child(3)'),
                intro: "View and process incoming orders",
                position: "right"
            },
            {
                element: document.querySelector('.sidebar-menu-items a:nth-child(4)'),
                intro: "Analyze important metrics and statistics",
                position: "right"
            },
            {
                element: document.querySelector('.sidebar-menu-items a:nth-child(5)'),
                intro: "Important recent events are just a click away",
                position: "right"
            },
            {
                element: document.querySelector('.sidebar-menu-items a:nth-child(6)'),
                intro: "Set up and personalise your store",
                position: "right"
            },
            {
                element: document.querySelector('.panel.panel-success'),
                intro: "Keep track of your order history",
                position: "right"
            },
            {
                element: document.querySelector('.panel.panel-primary'),
                intro: "Stay on top of your recent orders with ease!",
                position: "right"
            },
            {
                element: document.querySelector('.navbar-user'),
                intro: "Manage your account and logout when finished",
                position: "left"
            },
            {
                element: document.querySelector('.new-order-popup'),
                intro: "Get notified of new orders as they are placed!",
                position: "top"
            }
        ]
    });
    @if(config('bootlegcms.show_tutorial', false))
    tutorial.start();
    @endif
</script>
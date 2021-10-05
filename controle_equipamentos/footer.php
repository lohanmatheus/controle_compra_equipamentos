<script src="script/bootstrap.js"></script>
<script src="script/Api.js"></script>
<script>
    const logout = () => {
        if (confirm('Deseja realmente encerrar sua sessao de usuario?')) {
            localStorage.removeItem("products_add")
            localStorage.removeItem("quantity_product")
            location.href = 'logout.php';
        }
    }
</script>
</body>
</html>

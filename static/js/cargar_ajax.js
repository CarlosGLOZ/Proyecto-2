const body = document.getElementsByTagName('body')[0]
files = [
    'listar',
    'crear',
    'estados_mesa',
]

for (let i = 0; i < files.length; i++) {
    var newScript = document.createElement('script')
    newScript.src = '../static/js/ajax/' + files[i] + '.js'
    body.append(newScript)
}
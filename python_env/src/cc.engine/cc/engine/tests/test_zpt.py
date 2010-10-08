from cc.engine import util

util._activate_testing()


def test_zpt():
    template = util.get_zpt_template(
        'test/bunnies.pt')
    bunnies = [{'name': 'Lilian', 'description': 'lazy'},
               {'name': 'Tobias', 'description': 'furious'},
               {'name': 'Frank', 'description': 'grouch'}]
    template.pt_render({'bunnies': bunnies})

    test_results = util.ZPT_TEST_TEMPLATES.pop(template.filename)
    assert test_results['bunnies'] is bunnies
